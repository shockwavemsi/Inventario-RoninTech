<?php

namespace App\Http\Controllers;

use App\Models\DevolucionVenta;
use App\Models\DevolucionDetalle;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DevolucionesController extends Controller
{
    public function index()
    {
        $config = Configuracion::first();
        $devoluciones = DevolucionVenta::with('usuario', 'venta', 'detalles.producto')->get();
        $ventas = Venta::where('estado', 'completada')->get();

        return view('admin.devoluciones.index', compact('config', 'devoluciones', 'ventas'));
    }

    public function create()
{
    $config = Configuracion::first();

    // ✅ SOLO MOSTRAR VENTAS SIN DEVOLUCIONES COMPLETADAS
    $ventasConDevolucion = DevolucionVenta::where('estado', 'completada')
        ->pluck('venta_id')
        ->toArray();

    $ventas = Venta::where('estado', 'completada')
        ->whereNotIn('id', $ventasConDevolucion)
        ->with('detalles.producto')
        ->get();

    return view('admin.devoluciones.create', compact('config', 'ventas'));
}

// ✅ CAMBIAR ESTADO (de pendiente a completada)
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

                \Log::info("Stock aumentado: Producto {$producto->id}, +{$detalle->cantidad}");
            }
        }

        $devolucion->estado = $nuevoEstado;
        $devolucion->save();

        return response()->json([
            'success' => true,
            'message' => '✅ Devolución completada. Stock actualizado.'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => '❌ Error: ' . $e->getMessage()
        ], 500);
    }
}

    public function store(Request $request)
{
    try {
        \Log::info('=== INICIO GUARDAR DEVOLUCIÓN ===');

        $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'motivo' => 'required|string',
            'productos_json' => 'required|string',
            'total_devuelto' => 'required|numeric|min:0',
            'estado' => 'required|string',
        ]);

        \Log::info('Validación pasada');

        // ✅ VALIDAR QUE NO EXISTA DEVOLUCIÓN COMPLETADA
        $ventaId = $request->venta_id;
        $devolucionExistente = DevolucionVenta::where('venta_id', $ventaId)
            ->where('estado', 'completada')
            ->first();

        if ($devolucionExistente) {
            return back()->withInput()->with('error', 
                "❌ Esta venta ya tiene una devolución completada. No se puede crear otra."
            );
        }

        // Obtener venta
        $venta = Venta::with('detalles.producto')->findOrFail($ventaId);
        $productos = json_decode($request->productos_json, true);

        // ✅ VALIDAR QUE NO SUPERE CANTIDADES ORIGINALES
        foreach ($productos as $productoDevolucion) {
            $detalleOriginal = $venta->detalles->firstWhere('producto_id', $productoDevolucion['producto_id']);

            if (!$detalleOriginal) {
                return back()->withInput()->with('error', "❌ Producto no encontrado en la venta original");
            }

            if ($productoDevolucion['cantidad'] > $detalleOriginal->cantidad) {
                $producto = $detalleOriginal->producto;
                return back()->withInput()->with('error', 
                    "❌ No puedes devolver más de {$detalleOriginal->cantidad} unidades de '{$producto->nombre}'"
                );
            }
        }

        // Crear devolución
        $devolucion = DevolucionVenta::create([
            'venta_id' => $venta->id,
            'fecha' => now(),
            'motivo' => $request->motivo,
            'total_devuelto' => $request->total_devuelto,
            'usuario_id' => auth()->id(),
            'estado' => $request->estado,
        ]);

        foreach ($productos as $producto) {
            DevolucionDetalle::create([
                'devolucion_venta_id' => $devolucion->id,
                'producto_id' => $producto['producto_id'],
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio_unitario'],
                'subtotal' => $producto['subtotal']
            ]);

            if ($devolucion->estado === 'completada') {
                $prod = Producto::findOrFail($producto['producto_id']);
                $prod->increment('stock_actual', $producto['cantidad']);
            }

            $this->registrarMovimientoStock(
                $producto['producto_id'],
                'devolucion_venta',
                $producto['cantidad'],
                'devolucion_venta',
                $devolucion->id
            );
        }

        return redirect()->route('devoluciones.index')
                        ->with('success', "✅ ¡Devolución registrada correctamente!");

    } catch (\Exception $e) {
        \Log::error('ERROR AL GUARDAR DEVOLUCIÓN: ' . $e->getMessage());
        return back()->withInput()->with('error', '❌ Error: ' . $e->getMessage());
    }
}

    public function destroy($id)
    {
        try {
            $devolucion = DevolucionVenta::with('detalles.producto')->findOrFail($id);

            // ✅ REVERTIR stock cuando se elimina devolución
            foreach ($devolucion->detalles as $detalle) {
                $producto = $detalle->producto;
                $producto->decrement('stock_actual', $detalle->cantidad);
            }

            $devolucion->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '¡Devolución eliminada correctamente!'
                ]);
            }

            return redirect()->route('devoluciones.index')
                           ->with('success', '¡Devolución eliminada correctamente!');

        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la devolución: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error al eliminar la devolución');
        }
    }

    // ✅ Registrar movimiento de stock
    private function registrarMovimientoStock($productoId, $tipo, $cantidad, $referenciaTipo, $referenciaId)
    {
        // Calcular stock actual
        $stockActual = DB::table('movimientos_stock')
            ->where('producto_id', $productoId)
            ->sum(DB::raw("CASE WHEN tipo IN ('entrada_compra', 'devolucion_venta', 'inventario_inicial') THEN cantidad ELSE -cantidad END"));

        $stockNuevo = $stockActual + $cantidad;  // ← SUMA (es devolución)

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