<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentasController extends Controller
{
    /**
     * Mostrar lista de ventas
     */
    public function index()
    {
        try {
            $ventas = Venta::with('usuario', 'detalles.producto')
                ->orderBy('fecha_venta', 'desc')
                ->get()
                ->map(fn($v) => [
                    'id' => (int) $v->id,
                    'numero_factura' => $v->numero_factura,
                    'cliente' => $v->cliente,
                    'fecha_venta' => $v->fecha_venta?->format('d/m/Y') ?? '—',
                    'metodo_pago' => $v->metodo_pago,
                    'total' => (float) $v->total,
                    'estado' => $v->estado,
                    'productos' => $v->detalles->map(fn($d) => [
                        'id' => (int) $d->id,
                        'nombre' => $d->producto->nombre,
                        'cantidad' => (int) $d->cantidad,
                        'precio_unitario' => (float) $d->precio_unitario,
                        'subtotal' => (float) $d->subtotal,
                    ])->toArray(),
                ])
                ->toArray();

            $ultimoVentaId = Venta::latest('id')->first()?->id ?? 0;

            $metodosPago = DB::table('formas_pago')
                ->where('activo', true)
                ->whereIn('nombre', ['Efectivo', 'Tarjeta de Crédito'])
                ->get();

            return view('admin.ventas.index', compact('ventas', 'ultimoVentaId', 'metodosPago'));

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Buscar productos dinámicamente
     */
    public function buscarProductos(Request $request)
    {
        $query = $request->query('q', '');
        $productos = DB::table('productos')
            ->where('activo', true)
            ->where('stock_actual', '>', 0)
            ->where(function($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%")
                  ->orWhere('marca', 'LIKE', "%{$query}%")
                  ->orWhere('modelo', 'LIKE', "%{$query}%");
            })
            ->select(
                'id',
                'nombre',
                'marca',
                'modelo',
                'precio_venta_final',
                'stock_actual'
            )
            ->limit(5)
            ->get()
            ->map(fn($p) => [
                'id' => (int) $p->id,
                'nombre' => $p->nombre,
                'marca' => $p->marca,
                'modelo' => $p->modelo,
                'precio' => (float) $p->precio_venta_final,
                'stock' => (int) $p->stock_actual,
            ]);

        return response()->json($productos);
    }

    /**
     * Obtener próximo número de factura
     */
    public function proximoNumero()
    {
        try {
            $ultimaVenta = Venta::orderBy('id', 'desc')->first();

            if ($ultimaVenta) {
                $numero = (int)substr($ultimaVenta->numero_factura, 2) + 1;
            } else {
                $numero = 1;
            }

            $numeroFactura = 'V-' . str_pad($numero, 3, '0', STR_PAD_LEFT);

            \Log::info('📊 Próximo número generado:', ['numero' => $numeroFactura]);

            return response()->json(['numero' => $numeroFactura]);

        } catch (\Exception $e) {
            \Log::error('❌ Error generar número:', ['error' => $e->getMessage()]);
            return response()->json(['numero' => 'V-001'], 200);
        }
    }

    /**
     * Crear nueva venta
     */
    public function store(Request $request)
    {
        try {
            \Log::info('=== [VENTAS] INICIO CREAR VENTA ===');
            \Log::info('📋 Datos recibidos:', $request->all());

            // ✅ PARSEAR JSON DE LÍNEAS
            $lineasJson = $request->input('lineas');
            if (is_string($lineasJson)) {
                $lineas = json_decode($lineasJson, true);
                \Log::info('✅ Líneas parseadas:', $lineas);
            } else {
                $lineas = $lineasJson;
            }

            // ✅ VALIDAR
            $validated = $request->validate([
                'numero_factura' => 'required|unique:ventas',
                'cliente' => 'required|string|max:200',
                'cliente_documento' => 'nullable|string|max:20',
                'metodo_pago_id' => 'required|exists:formas_pago,id',
                'estado' => 'required|in:pendiente,completada,cancelada',
                'observaciones' => 'nullable|string',
                'iva_porcentaje' => 'nullable|numeric|min:0|max:100',
            ]);

            // ✅ VALIDAR LÍNEAS MANUALMENTE
            if (empty($lineas) || !is_array($lineas)) {
                throw new \Exception('Debes agregar al menos una línea');
            }

            foreach ($lineas as $idx => $linea) {
                if (empty($linea['producto_id']) || empty($linea['cantidad']) || empty($linea['precio_unitario'])) {
                    throw new \Exception("Línea " . ($idx + 1) . " incompleta");
                }

                // Validar que el producto existe
                $producto = Producto::findOrFail($linea['producto_id']);

                // Validar stock
                if ($producto->stock_actual < $linea['cantidad']) {
                    throw new \Exception("Stock insuficiente de '{$producto->nombre}'. Stock: {$producto->stock_actual}, Solicitado: {$linea['cantidad']}");
                }
            }

            \Log::info('✅ Validación pasada');

            // ✅ MAPEAR MÉTODO DE PAGO A ENUM
            $metodo = DB::table('formas_pago')->find($validated['metodo_pago_id']);

            $metodoMap = [
                'Efectivo' => 'efectivo',
                'Tarjeta de Crédito' => 'tarjeta',
                'Transferencia' => 'transferencia',
                'Crédito' => 'credito',
            ];

            $metodoEnum = $metodoMap[$metodo->nombre] ?? 'efectivo';

            \Log::info('💳 Mapeando método pago:', [
                'id' => $validated['metodo_pago_id'],
                'nombre' => $metodo->nombre,
                'enum' => $metodoEnum
            ]);

            // ✅ CREAR VENTA
            $venta = Venta::create([
                'numero_factura' => $validated['numero_factura'],
                'cliente' => $validated['cliente'],
                'cliente_documento' => $validated['cliente_documento'] ?? null,
                'metodo_pago' => $metodoEnum,  // ✅ USAR VALOR DEL ENUM
                'estado' => $validated['estado'],
                'usuario_id' => auth()->id(),
                'observaciones' => $validated['observaciones'] ?? null,
            ]);

            \Log::info('✅ Venta creada:', ['id' => $venta->id, 'numero' => $venta->numero_factura]);

            // ✅ GUARDAR LÍNEAS Y RESTAR STOCK
            $subtotal = 0;
            foreach ($lineas as $linea) {
                $producto = Producto::findOrFail($linea['producto_id']);
                $importe = $linea['cantidad'] * $linea['precio_unitario'];

                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $linea['producto_id'],
                    'cantidad' => $linea['cantidad'],
                    'precio_unitario' => $linea['precio_unitario'],
                    'subtotal' => $importe,
                ]);

                \Log::info('📦 Línea agregada:', [
                    'producto_id' => $linea['producto_id'],
                    'cantidad' => $linea['cantidad'],
                    'subtotal' => $importe
                ]);

                // ✅ RESTAR STOCK
                $producto->decrement('stock_actual', $linea['cantidad']);

                $subtotal += $importe;
            }

            \Log::info('✅ Líneas guardadas');

            // ✅ CALCULAR TOTALES
            $ivaPorc = isset($validated['iva_porcentaje']) ? floatval($validated['iva_porcentaje']) : 21;
            $impuesto = $subtotal * ($ivaPorc / 100);
            $total = $subtotal + $impuesto;

            \Log::info('💰 Cálculo de totales:', [
                'subtotal' => $subtotal,
                'iva_porcentaje' => $ivaPorc,
                'impuesto' => $impuesto,
                'total' => $total
            ]);

            $venta->update([
                'subtotal' => $subtotal,
                'impuesto' => $impuesto,
                'total' => $total,
            ]);

            \Log::info('✅ Venta guardada exitosamente', [
                'numero' => $venta->numero_factura,
                'total' => $total,
                'metodo_pago' => $metodoEnum
            ]);

            return redirect()->route('ventas.index')
                ->with('success', "✅ Venta creada: {$venta->numero_factura} - Total: €" . number_format($total, 2, ',', '.'));

        } catch (\Exception $e) {
            \Log::error('❌ Error al crear venta: ' . $e->getMessage());
            \Log::error('Stack trace:', ['trace' => $e->getTraceAsString()]);

            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar venta (devolver stock)
     */
    public function destroy(Venta $venta)
    {
        try {
            foreach ($venta->detalles as $detalle) {
                $detalle->producto->increment('stock_actual', $detalle->cantidad);
            }

            $venta->delete();

            return redirect()->route('ventas.index')
                ->with('success', '✅ Venta eliminada y stock devuelto');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
 * Generar PDF de venta con Carbone
 */
public function generarPDF(Request $request)
{
    try {
        // 📋 Obtener configuración de empresa
        $config = DB::table('configuracion')->first();

        // 📋 Obtener venta con detalles
        $venta = Venta::with('detalles.producto', 'usuario')
            ->findOrFail($request->venta_id);

        // 📋 Preparar datos para Carbone
        $datos = [
            // Datos de la empresa (desde Configuración)
            'nombre_empresa' => $config->nombre_empresa ?? 'Tu Empresa',
            'cif' => $config->ruc ?? 'A12345678',
            'dir_empresa' => $config->direccion ?? 'Dirección',
            'telefono_empresa' => $config->telefono ?? '+34 XXX XXX XXX',
            'email_empresa' => $config->email ?? 'info@empresa.com',
            'web_empresa' => 'www.ronintech.es',

            // Datos de la venta
            'nFactura' => $venta->numero_factura,
            'Fecha_factura' => $venta->fecha_venta?->format('d/m/Y') ?? date('d/m/Y'),

            // Datos del cliente
            'cliente_nombre' => $venta->cliente,
            'cliente_dni' => $venta->cliente_documento ?? '—',
            'cliente_correo' => $venta->usuario->email ?? '—',
            'cliente_telf' => '—',

            // Método de pago
            'metodo_pago' => ucfirst(str_replace('_', ' ', $venta->metodo_pago)),
            'banco' => 'BBVA',
            'referencia_pago' => 'REF-' . strtoupper($venta->numero_factura),

            // Productos
            'productos' => $venta->detalles->map(function($detalle) {
                return [
                    'name' => $detalle->producto->nombre,
                    'cantidad' => (int) $detalle->cantidad,
                    'precio_unitario' => (float) $detalle->precio_unitario,
                    'precio_venta' => (float) $detalle->subtotal
                ];
            })->toArray(),

            // Totales
            'subTotal' => (float) $venta->subtotal,
            'IVA' => (float) $config->impuesto_porcentaje ?? 21,
            'cantidad_IVA' => (float) $venta->impuesto,
            'total_pagar' => (float) $venta->total,
        ];

        // 🔧 Renderizar con Carbone
        $carbone = new \App\Services\CarboneService();
        $pdf = $carbone->render('factura_venta', $datos, 'venta_' . time());

        return response()->download($pdf, "Factura_{$venta->numero_factura}.pdf");

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
    /**
     * Cambiar estado
     */
    public function cambiarEstado(Venta $venta, Request $request)
    {
        try {
            $venta->update(['estado' => $request->estado]);

            return response()->json(['success' => true, 'message' => '✅ Estado actualizado']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}