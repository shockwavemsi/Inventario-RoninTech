<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Producto;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentasController extends Controller
{
    public function index()
    {
        $config = Configuracion::first();
        $ventas = Venta::with('usuario', 'detalles.producto')->get();
        $productos = Producto::where('activo', true)->get();
        return view('admin.ventas.index', compact('config', 'ventas', 'productos'));
    }

    public function create()
    {
        $config = Configuracion::first();
        $productos = Producto::where('activo', true)->get();
        return view('admin.ventas.create', compact('config', 'productos'));
    }

    public function store(Request $request)
    {
        try {
            \Log::info('=== INICIO GUARDAR VENTA ===');

            $request->validate([
                'cliente' => 'required|string',
                'cliente_documento' => 'nullable|string',
                'metodo_pago' => 'required|string',
                'estado' => 'required|string',
                'observaciones' => 'nullable|string',
                'productos_json' => 'required|string',
                'subtotal' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
            ]);

            // ✅ VALIDAR PRODUCTOS ANTES DE CREAR VENTA
            $productos = json_decode($request->productos_json, true);

            foreach ($productos as $producto) {
                $prod = Producto::findOrFail($producto['producto_id']);

                // VALIDACIÓN 1: Producto agotado
                if ($prod->stock_actual <= 0) {
                    return back()->withInput()->with('error', "⚠️ El producto '{$prod->nombre}' está agotado. No hay stock disponible.");
                }

                // VALIDACIÓN 2: Sobrepasa stock actual
                if ($producto['cantidad'] > $prod->stock_actual) {
                    return back()->withInput()->with('error', "⚠️ No hay suficiente stock de '{$prod->nombre}'. Stock disponible: {$prod->stock_actual}, solicitado: {$producto['cantidad']}");
                }
            }

            // Generar número de venta automático
            $ultimaVenta = Venta::orderBy('id', 'desc')->first();
            $numero = $ultimaVenta ? (int)substr($ultimaVenta->numero_factura, 2) + 1 : 1;
            $numero_factura = 'V-' . str_pad($numero, 3, '0', STR_PAD_LEFT);

            // Crear la venta
            $datos = $request->all();
            $datos['usuario_id'] = auth()->id();
            $datos['numero_factura'] = $numero_factura;
            $datos['fecha_venta'] = now();

            $venta = Venta::create($datos);

            \Log::info('Venta creada ID: ' . $venta->id . ' Código: ' . $numero_factura);

            // Guardar los productos
            foreach ($productos as $producto) {
                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto['producto_id'],
                    'cantidad' => $producto['cantidad'],
                    'precio_unitario' => $producto['precio_unitario'],
                    'subtotal' => $producto['subtotal']
                ]);

                // ✅ SOLO RESTAR SI ESTÁ COMPLETADA
                if ($venta->estado === 'completada') {
                    $prod = Producto::findOrFail($producto['producto_id']);
                    $prod->decrement('stock_actual', $producto['cantidad']);
                }

                // Registrar movimiento de stock
                $this->registrarMovimientoStock(
                    $producto['producto_id'],
                    'salida_venta',
                    $producto['cantidad'],
                    'venta',
                    $venta->id
                );
            }

            \Log::info('Venta guardada exitosamente');

            return redirect()->route('ventas.index')
                            ->with('success', "✅ ¡Venta creada correctamente! Número: {$numero_factura}");

        } catch (\Exception $e) {
            \Log::error('ERROR AL GUARDAR VENTA: ' . $e->getMessage());
            return back()->withInput()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $venta = Venta::findOrFail($id);
            $venta->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '¡Venta eliminada correctamente!'
                ]);
            }

            return redirect()->route('ventas.index')
                           ->with('success', '¡Venta eliminada correctamente!');

        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la venta: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error al eliminar la venta');
        }
    }

    // ✅ CAMBIAR ESTADO DE VENTA
    public function cambiarEstado(Request $request, $id)
    {
        try {
            $venta = Venta::with('detalles.producto')->findOrFail($id);
            $nuevoEstado = $request->input('estado');

            // Si cambia a completada desde pendiente, RESTAR STOCK
            if ($nuevoEstado === 'completada' && $venta->estado === 'pendiente') {
                foreach ($venta->detalles as $detalle) {
                    $producto = $detalle->producto;

                    // Validar que todavía hay stock
                    if ($producto->stock_actual < $detalle->cantidad) {
                        return response()->json([
                            'success' => false,
                            'message' => "⚠️ Stock insuficiente para '{$producto->nombre}'. Disponible: {$producto->stock_actual}, necesario: {$detalle->cantidad}"
                        ], 400);
                    }

                    $producto->decrement('stock_actual', $detalle->cantidad);
                }
            }

            $venta->estado = $nuevoEstado;
            $venta->save();

            return response()->json([
                'success' => true,
                'message' => '✅ Venta actualizada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // ✅ REGISTRAR MOVIMIENTO DE STOCK
    private function registrarMovimientoStock($productoId, $tipo, $cantidad, $referenciaTipo, $referenciaId)
    {
        $stockActual = DB::table('movimientos_stock')
            ->where('producto_id', $productoId)
            ->sum(DB::raw("CASE WHEN tipo IN ('entrada_compra', 'devolucion_venta', 'inventario_inicial') THEN cantidad ELSE -cantidad END"));

        $stockNuevo = $stockActual - $cantidad;

        DB::table('movimientos_stock')->insert([
            'producto_id' => $productoId,
            'tipo' => $tipo,
            'cantidad' => $cantidad,
            'stock_anterior' => $stockActual,
            'stock_nuevo' => $stockNuevo,
            'referencia_tipo' => $referenciaTipo,
            'referencia_id' => $referenciaId,
            'usuario_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \Log::info("Movimiento stock registrado: Producto {$productoId}, Tipo {$tipo}, Cantidad {$cantidad}");
    }
}