<?php

namespace App\Http\Controllers;

use App\Models\DevolucionVenta;
use App\Models\DevolucionDetalle;
use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DevolucionesController extends Controller
{
    public function index()
    {
        try {
            $devoluciones = DevolucionVenta::with('usuario', 'venta', 'detalles.producto')
                ->orderBy('fecha', 'desc')
                ->get()
                ->map(fn($d) => [
                    'id' => (int) $d->id,
                    'numero' => 'DEV-' . str_pad($d->id, 4, '0', STR_PAD_LEFT),
                    'fecha' => $d->fecha->format('d/m/Y'),
                    'cliente' => $d->venta->cliente ?? '—',
                    'producto' => $d->detalles->first()?->producto->nombre ?? '—',
                    'total' => (float) $d->total_devuelto,
                    'estado' => $d->estado,
                    'motivo' => $d->motivo,
                    'usuario' => $d->usuario->name ?? 'Usuario',
                    'detalles' => $d->detalles->map(fn($det) => [
                        'producto_id' => (int) $det->producto_id,
                        'producto_nombre' => $det->producto->nombre,
                        'cantidad' => (int) $det->cantidad,
                        'precio_unitario' => (float) $det->precio_unitario,
                        'subtotal' => (float) $det->subtotal,
                    ])->toArray(),
                ])
                ->toArray();

            $totalDevoluciones = count($devoluciones);
            $pendientes = count(array_filter($devoluciones, fn($d) => $d['estado'] === 'pendiente'));
            $completadas = count(array_filter($devoluciones, fn($d) => $d['estado'] === 'completada'));
            $valorTotal = array_sum(array_column($devoluciones, 'total'));

            return view('admin.devoluciones.index', compact(
                'devoluciones',
                'totalDevoluciones',
                'pendientes',
                'completadas',
                'valorTotal'
            ));

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            \Log::info('=== [DEVOLUCIONES] INICIO CREAR DEVOLUCIÓN ===');
            \Log::info('📋 Datos recibidos:', $request->all());

            // Validar
            $request->validate([
                'venta_id' => 'required|exists:ventas,id',
                'motivo' => 'required|string',
                'productos_json' => 'required|string',
                'total_devuelto' => 'required|numeric|min:0',
                'estado' => 'required|string|in:pendiente,completada',
            ]);

            // 1. Parsear JSON de productos
            $productos = json_decode($request->productos_json, true);
            
            if (!is_array($productos) || empty($productos)) {
                \Log::error('❌ Error al crear devolución: Debes agregar al menos una línea');
                return back()->withInput()->with('error', 'Debes seleccionar al menos un producto a devolver');
            }

            \Log::info('✅ Productos parseados:', $productos);

            // 2. Obtener venta
            $venta = Venta::with('detalles.producto')->findOrFail($request->venta_id);
            \Log::info('✅ Venta obtenida:', ['id' => $venta->id, 'numero' => $venta->numero_factura]);

            // 3. Validar cantidades vs cantidad original
            foreach ($productos as $productoDevolucion) {
                $detalleOriginal = $venta->detalles->firstWhere('producto_id', $productoDevolucion['producto_id']);

                if (!$detalleOriginal) {
                    \Log::error('❌ Producto no encontrado en venta:', $productoDevolucion);
                    return back()->withInput()->with('error', 'Producto no encontrado en la venta');
                }

                if ($productoDevolucion['cantidad'] > $detalleOriginal->cantidad) {
                    \Log::error('❌ Devolución supera cantidad original:', [
                        'solicitado' => $productoDevolucion['cantidad'],
                        'original' => $detalleOriginal->cantidad,
                    ]);
                    return back()->withInput()->with('error', 
                        "No puedes devolver más de {$detalleOriginal->cantidad} unidades"
                    );
                }
            }

            // 4. Crear devolución
            $devolucion = DevolucionVenta::create([
                'venta_id' => $venta->id,
                'fecha' => now(),
                'motivo' => $request->motivo,
                'total_devuelto' => (float) $request->total_devuelto,
                'usuario_id' => auth()->id(),
                'estado' => $request->estado,
            ]);

            \Log::info('✅ Devolución creada:', ['id' => $devolucion->id]);

            // 5. Crear detalles y AUMENTAR stock
            foreach ($productos as $producto) {
                // Crear detalle
                DevolucionDetalle::create([
                    'devolucion_venta_id' => $devolucion->id,
                    'producto_id' => (int) $producto['producto_id'],
                    'cantidad' => (int) $producto['cantidad'],
                    'precio_unitario' => (float) $producto['precio_unitario'],
                    'subtotal' => (float) $producto['subtotal'],
                ]);

                // ✅ AUMENTAR STOCK si estado es completada
                if ($devolucion->estado === 'completada') {
                    $prod = Producto::findOrFail($producto['producto_id']);
                    $prod->increment('stock_actual', (int) $producto['cantidad']);
                    \Log::info("✅ Stock aumentado: Producto {$prod->id} (+{$producto['cantidad']})", [
                        'stock_anterior' => $prod->stock_actual - $producto['cantidad'],
                        'stock_nuevo' => $prod->stock_actual,
                    ]);
                }

                // Registrar movimiento de stock
                $this->registrarMovimientoStock(
                    (int) $producto['producto_id'],
                    'devolucion_venta',
                    (int) $producto['cantidad'],
                    $devolucion->id
                );
            }

            \Log::info('✅ Devolución guardada exitosamente', ['id' => $devolucion->id, 'estado' => $devolucion->estado]);

            return redirect()->route('devoluciones.index')
                           ->with('success', '✅ Devolución registrada correctamente');

        } catch (\Exception $e) {
            \Log::error('❌ Error al guardar devolución: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function cambiarEstado(Request $request, $id)
    {
        try {
            $devolucion = DevolucionVenta::with('detalles.producto')->findOrFail($id);
            $nuevoEstado = $request->input('estado');

            // Si cambia a completada desde pendiente, AUMENTAR stock
            if ($nuevoEstado === 'completada' && $devolucion->estado === 'pendiente') {
                foreach ($devolucion->detalles as $detalle) {
                    $producto = $detalle->producto;
                    $producto->increment('stock_actual', $detalle->cantidad);

                    \Log::info("✅ Stock aumentado (cambio estado): Producto {$producto->id}, +{$detalle->cantidad}");
                }
            }

            $devolucion->estado = $nuevoEstado;
            $devolucion->save();

            return response()->json([
                'success' => true,
                'message' => '✅ Devolución completada. Stock actualizado.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al cambiar estado: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '❌ Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $devolucion = DevolucionVenta::with('detalles.producto')->findOrFail($id);

            // ✅ REVERTIR stock si está completada
            if ($devolucion->estado === 'completada') {
                foreach ($devolucion->detalles as $detalle) {
                    $producto = $detalle->producto;
                    $producto->decrement('stock_actual', $detalle->cantidad);
                    \Log::info("✅ Stock revertido (eliminación): Producto {$producto->id}, -{$detalle->cantidad}");
                }
            }

            $devolucion->delete();

            return redirect()->route('devoluciones.index')
                           ->with('success', '✅ Devolución eliminada correctamente');

        } catch (\Exception $e) {
            \Log::error('Error al eliminar devolución: ' . $e->getMessage());
            return back()->with('error', 'Error al eliminar la devolución');
        }
    }

    // ✅ Registrar movimiento de stock
    private function registrarMovimientoStock($productoId, $tipo, $cantidad, $referenciaId)
    {
        try {
            $stockActual = DB::table('movimientos_stock')
                ->where('producto_id', $productoId)
                ->sum(DB::raw("CASE WHEN tipo IN ('entrada_compra', 'devolucion_venta', 'inventario_inicial') THEN cantidad ELSE -cantidad END"));

            $stockNuevo = $stockActual + $cantidad;

            DB::table('movimientos_stock')->insert([
                'producto_id' => $productoId,
                'tipo' => $tipo,
                'cantidad' => $cantidad,
                'stock_anterior' => $stockActual,
                'stock_nuevo' => $stockNuevo,
                'referencia_tipo' => $tipo,
                'referencia_id' => $referenciaId,
                'usuario_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            \Log::info("✅ Movimiento stock registrado: Producto {$productoId}, Tipo {$tipo}, Cantidad {$cantidad}");

        } catch (\Exception $e) {
            \Log::warning("⚠️ Error al registrar movimiento de stock: " . $e->getMessage());
        }
    }
}
