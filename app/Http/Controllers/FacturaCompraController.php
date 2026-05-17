<?php

namespace App\Http\Controllers;

use App\Models\FacturaCompra;
use App\Models\AlbaranCompra;
use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\PedidoCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacturaCompraController extends Controller
{
    /**
     * Mostrar lista de facturas
     */
    /**
     * Mostrar lista de facturas
     */
    public function index()
    {
        try {
            $facturas = FacturaCompra::with('albaranCompra', 'proveedor')
                ->orderBy('fecha_factura', 'desc')
                ->paginate(15);

            // ✅ Obtener todos los datos que la vista necesita
            $proveedores = \App\Models\Proveedor::where('activo', 1)->get();
            $productos = \App\Models\Producto::where('activo', 1)->get();
            $pedidos = \App\Models\PedidoCompra::whereIn('estado', ['abierto', 'parcial'])->get();
            $albaranes = \App\Models\AlbaranCompra::where('estado', 'recibido')->get();

            $ultimoPedidoId = \DB::table('pedidos_compra')->latest('id')->first()?->id ?? 0;
            $ultimoAlbaranId = \DB::table('albaranes_compra')->latest('id')->first()?->id ?? 0;
            $ultimoFacturaId = \DB::table('facturas_compra')->latest('id')->first()?->id ?? 0;

            return view('admin.compras.index', compact(
                'facturas',
                'proveedores',
                'productos',
                'pedidos',
                'albaranes',
                'ultimoPedidoId',
                'ultimoAlbaranId',
                'ultimoFacturaId'
            ));

        } catch (\Exception $e) {
            \Log::error('Error en index facturas:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Obtener todas las facturas vía AJAX
     */
    public function getAll()
    {
        $facturas = FacturaCompra::with('albaranCompra', 'proveedor')
            ->orderBy('fecha_factura', 'desc')
            ->get()
            ->map(fn($f) => [
                'id' => (int) $f->id,
                'numero_factura' => $f->numero_factura ?? 'N/A',
                'proveedor' => $f->proveedor?->nombre ?? '—',
                'albaran' => $f->albaranCompra?->numero_albaran ?? '—',
                'fecha_factura' => is_string($f->fecha_factura)
                    ? $f->fecha_factura
                    : $f->fecha_factura?->format('Y-m-d') ?? '—',
                'fecha_vencimiento' => is_string($f->fecha_vencimiento)
                    ? $f->fecha_vencimiento
                    : $f->fecha_vencimiento?->format('Y-m-d') ?? '—',
                'estado' => $f->estado ?? 'abierta',
                'total' => (float) ($f->total ?? 0),
                'type' => 'factura',
                'created_at' => is_string($f->created_at)
                    ? $f->created_at
                    : $f->created_at?->format('Y-m-d') ?? '—'
            ]);

        return response()->json($facturas);
    }

    /**
     * Obtener detalles completos de una factura en JSON (para modal)
     */
    public function showJson(FacturaCompra $facturaCompra)
    {
        try {
            $facturaCompra->load([
                'albaranCompra.lineas.producto',
                'proveedor',
                'pagos'
            ]);

            $formatDate = function($date) {
                if (is_string($date)) return $date;
                return $date ? $date->format('Y-m-d') : '—';
            };

            // ✅ CALCULAR TOTALES DE PAGOS
            $totalPagado = $facturaCompra->pagos->sum('monto') ?? 0;
            $totalFactura = (float) ($facturaCompra->total ?? 0);
            $pendiente = $totalFactura - $totalPagado;
            
            // ✅ DETERMINAR ESTADO SEGÚN PAGOS
            $estado = $facturaCompra->estado;
            if ($totalPagado >= $totalFactura && $totalFactura > 0) {
                $estado = 'pagada';
                $facturaCompra->update(['estado' => 'pagada']);
            } elseif ($totalPagado > 0 && $totalPagado < $totalFactura) {
                $estado = 'parcial';
            }

            return response()->json([
                'id' => (int) $facturaCompra->id,
                'numero_factura' => (string) $facturaCompra->numero_factura,
                'proveedor' => $facturaCompra->proveedor?->nombre ?? '—',
                'estado' => (string) $estado,
                'fecha_factura' => $formatDate($facturaCompra->fecha_factura),
                'fecha_vencimiento' => $formatDate($facturaCompra->fecha_vencimiento),
                'total' => (float) $totalFactura,
                'total_pagado' => (float) $totalPagado,
                'pendiente' => (float) max(0, $pendiente),
                'observaciones' => (string) ($facturaCompra->observaciones ?? ''),
                'lineas' => [],
                'pagos' => $facturaCompra->pagos ? $facturaCompra->pagos->map(fn($p) => [
                    'id' => (int) $p->id,
                    'monto' => (float) ($p->monto ?? 0),
                    'fecha' => $formatDate($p->fecha_pago),
                    'estado' => (string) ($p->estado ?? 'pendiente'),
                    'referencia' => (string) ($p->referencia ?? '—'),
                ]) : [],
                'albaran_numero' => $facturaCompra->albaranCompra?->numero_albaran ?? '—',
                'albaran_id' => $facturaCompra->albaranCompra?->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en showJson Factura:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Buscar albaranes para crear factura
     */
    public function buscarAlbaranes(Request $request)
    {
        try {
            $query = $request->query('q', '');
            
            $albaranes = \DB::table('albaranes_compra')
                ->join('proveedores', 'albaranes_compra.proveedor_id', '=', 'proveedores.id')
                ->where('albaranes_compra.numero_albaran', 'LIKE', "%{$query}%")
                ->select(
                    'albaranes_compra.id',
                    'albaranes_compra.numero_albaran',
                    'albaranes_compra.total',
                    'albaranes_compra.estado',
                    'proveedores.nombre as proveedor'
                )
                ->limit(10)
                ->get();

            return response()->json($albaranes);
        } catch (\Exception $e) {
            \Log::error('Error en buscarAlbaranes:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtener formas de pago de un proveedor
     */
    public function obtenerFormasPago($proveedorId)
    {
        try {
            $formas = \DB::table('formas_pago_proveedor')
                ->where('proveedor_id', $proveedorId)
                ->join('formas_pago', 'formas_pago_proveedor.forma_pago_id', '=', 'formas_pago.id')
                ->leftJoin('bancos', 'formas_pago_proveedor.banco_id', '=', 'bancos.id')
                ->select(
                    'formas_pago_proveedor.id',
                    'formas_pago.nombre as forma_pago',
                    'bancos.nombre as banco_nombre',
                    'formas_pago_proveedor.referencia'
                )
                ->orderBy('formas_pago_proveedor.id')
                ->get();

            return response()->json($formas);
        } catch (\Exception $e) {
            \Log::error('Error en obtenerFormasPago:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtener días de vencimiento de un proveedor
     */
    public function obtenerDiasVencimiento($proveedorId)
    {
        try {
            $dias = \DB::table('dias_vencimiento_proveedores')
                ->where('proveedor_id', $proveedorId)
                ->select('dias_vencimiento')
                ->first();

            $diasVencimiento = $dias ? (int)$dias->dias_vencimiento : 30;

            return response()->json([
                'dias' => $diasVencimiento,
                'proveedor_id' => $proveedorId
            ]);
        } catch (\Exception $e) {
            \Log::error('Error obtener días vencimiento:', ['error' => $e->getMessage()]);
            return response()->json(['dias' => 30], 200);
        }
    }

    /**
     * Crear nueva factura
     */
    public function store(Request $request)
    {
        try {
            \Log::info('📥 Datos recibidos en store:', $request->all());

            // ✅ VALIDAR DATOS OBLIGATORIOS
            $validated = $request->validate([
                'numero_factura' => 'required|unique:facturas_compra,numero_factura',
                'albaran_compra_id' => 'required|exists:albaranes_compra,id',
                'fecha_factura' => 'required|date',
                'fecha_vencimiento' => 'nullable|date',
                'observaciones' => 'nullable|string',
            ]);

            // ✅ CARGAR ALBARÁN
            $albaran = AlbaranCompra::with('lineas.producto')->find($validated['albaran_compra_id']);
            if (!$albaran) {
                throw new \Exception('Albarán no encontrado');
            }

            $validated['proveedor_id'] = $albaran->proveedor_id;
            $validated['estado'] = 'abierta';
            $validated['total'] = (float) $albaran->total;

            // ✅ CREAR FACTURA
            $factura = FacturaCompra::create($validated);

            \Log::info('✅ Factura creada:', ['id' => $factura->id, 'numero' => $factura->numero_factura]);

            // ✅ COPIAR LÍNEAS DEL ALBARÁN A LA FACTURA
            if ($albaran->lineas && $albaran->lineas->count() > 0) {
                foreach ($albaran->lineas as $lineaAlbaran) {
                    if ($lineaAlbaran->cantidad_recibida > 0 && $lineaAlbaran->producto) {
                        
                        // ✅ OBTENER PRECIO BASE DEL PRODUCTO
                        $precioBase = (float) ($lineaAlbaran->producto->precio_compra ?? 0);
                        $iva = 21.00; // IVA default
                        $precioFinal = $precioBase * (1 + ($iva / 100));
                        
                        // ✅ INSERTAR CON LAS COLUMNAS CORRECTAS
                        \DB::table('facturas_compra_linea')->insert([
                            'factura_compra_id' => $factura->id,
                            'producto_id' => $lineaAlbaran->producto_id,
                            'cantidad' => (int) $lineaAlbaran->cantidad_recibida,
                            'precio_base_compra' => $precioBase,
                            'porcentaje_iva_compra' => $iva,
                            'precio_compra_final' => $precioFinal,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        \Log::info('✅ Línea guardada:', [
                            'producto_id' => $lineaAlbaran->producto_id,
                            'cantidad' => $lineaAlbaran->cantidad_recibida,
                            'precio_base' => $precioBase,
                            'precio_final' => $precioFinal
                        ]);
                    }
                }
                \Log::info('✅ Todas las líneas copiadas desde albarán');
            }

            // ✅ GUARDAR PAGOS
            if ($request->has('forma_pago_id') && is_array($request->forma_pago_id)) {
                foreach ($request->forma_pago_id as $idx => $formaId) {
                    if (empty($formaId)) continue;

                    $monto = (float) ($request->monto_pago[$idx] ?? 0);
                    
                    if ($formaId > 0 && $monto > 0) {
                        \DB::table('pagos_factura')->insert([
                            'factura_compra_id' => $factura->id,
                            'forma_pago_proveedor_id' => (int) $formaId,
                            'monto' => $monto,
                            'fecha_pago' => $request->fecha_pago[$idx] ?? now(),
                            'referencia' => $request->referencia_pago[$idx] ?? '',
                            'estado' => $request->estado_pago[$idx] ?? 'pendiente',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        \Log::info('✅ Pago guardado:', [
                            'forma_id' => $formaId,
                            'monto' => $monto,
                            'estado' => $request->estado_pago[$idx] ?? 'pendiente'
                        ]);
                    }
                }
            }

            \Log::info('✅ Factura guardada completamente:', ['id' => $factura->id]);

            return redirect()->route('facturas-compra.index')
                ->with('success', '✅ Factura creada correctamente: ' . $factura->numero_factura);

        } catch (\Exception $e) {
            \Log::error('❌ Error en store:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar una factura
     */
    public function destroy(FacturaCompra $facturaCompra)
    {
        try {
            $facturaCompra->delete();
            return response()->json(['success' => true, 'message' => '✅ Factura eliminada']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => '❌ Error: ' . $e->getMessage()], 500);
        }
    }
}
