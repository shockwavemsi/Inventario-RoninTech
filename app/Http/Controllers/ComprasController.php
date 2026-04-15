<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\CompraDetalle;
use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\Configuracion;
use Illuminate\Http\Request;

class ComprasController extends Controller
{
public function index()
{
    $config = Configuracion::first();
    $compras = Compra::with('proveedor', 'usuario', 'detalles.producto')->get();
    $proveedores = Proveedor::all();
    $productos = Producto::where('activo', true)->get();
    
    // Cambia esto:
    // return view('admin.compras', compact(...));
    
    // Por esto:
    return view('admin.compras.index', compact('config', 'compras', 'proveedores', 'productos'));
}

    // ✅ NUEVO MÉTODO: Muestra el formulario de creación
    public function create()
    {
        $config = Configuracion::first();
        $proveedores = Proveedor::all();
        $productos = Producto::where('activo', true)->get();
        
        return view('admin.compras.create', compact('config', 'proveedores', 'productos'));
    }

    public function store(Request $request)
{
    try {
        \Log::info('=== INICIO GUARDAR COMPRA ===');
        \Log::info('Datos recibidos:', $request->all());
        
        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha_pedido' => 'required|date',
            'fecha_entrega_esperada' => 'nullable|date',
            'subtotal' => 'required|numeric|min:0',
            'impuesto' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string',
            'productos_json' => 'required|string',
        ]);
        
        \Log::info('Validación pasada');
        
        // Crear la compra
        $datos = $request->all();
        $datos['usuario_id'] = auth()->id();
        
        \Log::info('Datos a guardar:', $datos);
        
        $compra = Compra::create($datos);
        
        \Log::info('Compra creada ID: ' . $compra->id);
        
        // Guardar los productos
        $productos = json_decode($request->productos_json, true);
        
        \Log::info('Productos a guardar:', $productos);
        
        foreach ($productos as $producto) {
            CompraDetalle::create([
                'compra_id' => $compra->id,
                'producto_id' => $producto['producto_id'],
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio_unitario'],
                'descuento' => $producto['descuento'] ?? 0,
                'subtotal' => $producto['subtotal']
            ]);
        }
        
        \Log::info('Compra guardada exitosamente');
        \Log::info('=== FIN GUARDAR COMPRA ===');
        
        return redirect()->route('compras.index')
                        ->with('success', "¡Compra creada correctamente! Número: {$compra->numero_factura}");
                        
    } catch (\Exception $e) {
        \Log::error('ERROR AL GUARDAR COMPRA: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
    }
}
    public function destroy($id)
    {
        try {
            $compra = Compra::findOrFail($id);
            $compra->delete();
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '¡Compra eliminada correctamente!'
                ]);
            }
            
            return redirect()->route('compras.index')
                           ->with('success', '¡Compra eliminada correctamente!');
                           
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la compra: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error al eliminar la compra');
        }
    }

public function cambiarEstado(Request $request, $id)
{
    try {
        $compra = Compra::with('detalles.producto')->findOrFail($id);
        $nuevoEstado = $request->input('estado');  // Para FormData
        
        if ($nuevoEstado === 'recibido' && $compra->estado === 'pendiente') {
            foreach ($compra->detalles as $detalle) {
                $producto = $detalle->producto;
                $producto->increment('stock_actual', $detalle->cantidad);
            }
        }
        
        $compra->estado = $nuevoEstado;
        $compra->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Compra recibida. Stock actualizado.'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
}