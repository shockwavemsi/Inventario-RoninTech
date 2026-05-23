<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Proveedor;        
use App\Models\Categoria;    
use App\Models\Configuracion;
use App\Models\TablaIva;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
public function index()
{
    $config = Configuracion::first();

    // ✅ Solo mostrar ACTIVOS
    $productos = Producto::where('activo', true)
        ->with('categoria', 'proveedor', 'ivaCompra', 'ivaVenta')
        ->orderBy('id', 'desc')
        ->paginate(10);

    $categorias = Categoria::all();
    $proveedores = Proveedor::all();
    $ivas = TablaIva::where('activo', true)->get();

    return view('admin.productos', compact('config', 'productos', 'categorias', 'proveedores', 'ivas'));
}

// Método para obtener UN producto en JSON
public function show($id)
{
    $producto = Producto::with('categoria', 'proveedor', 'ivaCompra', 'ivaVenta')->findOrFail($id);
    return response()->json($producto);
}

// Método para obtener LISTA de productos para el modal
public function listaParaModal(Request $request)
{
    $query = Producto::query()
        ->with('categoria', 'proveedor', 'ivaCompra', 'ivaVenta')
        ->select('id', 'nombre', 'categoria_id', 'proveedor_id', 'precio_compra_final', 'precio_venta_final', 'activo');

    // Buscar por nombre
    if ($request->has('buscar') && $request->buscar) {
        $query->where('nombre', 'like', '%' . $request->buscar . '%');
    }

    // Filtrar por estado
    if ($request->has('estado') && $request->estado !== '') {
        $query->where('activo', $request->estado);
    }

    // Filtrar por proveedor
    if ($request->has('proveedor') && $request->proveedor) {
        $query->where('proveedor_id', $request->proveedor);
    }

    $productos = $query->paginate(10);
    return response()->json($productos);
}

public function create()
{
    $config = Configuracion::first();
    $proveedores = Proveedor::all();
    $categorias = Categoria::all();
    return view('admin.productos_crear', compact('config', 'proveedores', 'categorias'));
}

    public function store(Request $request)
    {
        Producto::create($request->all());
        return redirect()->route('productos.index')->with('success', '!Producto creado correctamente!');
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'marca' => 'nullable|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'categoria_id' => 'nullable|exists:categorias,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'precio_base_compra' => 'nullable|numeric|min:0',
            'iva_compra_id' => 'nullable|exists:tabla_ivas,id',
            'precio_base_venta' => 'nullable|numeric|min:0',
            'iva_venta_id' => 'nullable|exists:tabla_ivas,id',
            'stock_minimo' => 'nullable|integer|min:0',
            'stock_maximo' => 'nullable|integer|min:0',
            'ubicacion' => 'nullable|string|max:100',
            'activo' => 'nullable|boolean',
        ]);

        $ivaCompraId = $validated['iva_compra_id'] ?? $producto->iva_compra_id;
        $ivaVentaId = $validated['iva_venta_id'] ?? $producto->iva_venta_id;

        $ivaCompra = !empty($ivaCompraId)
            ? (float) TablaIva::find($ivaCompraId)?->porcentaje
            : 0;

        $ivaVenta = !empty($ivaVentaId)
            ? (float) TablaIva::find($ivaVentaId)?->porcentaje
            : 0;

        $precioBaseCompra = (float) ($validated['precio_base_compra'] ?? $producto->precio_base_compra ?? 0);
        $precioBaseVenta = (float) ($validated['precio_base_venta'] ?? $producto->precio_base_venta ?? 0);

        $validated['iva_compra_id'] = $ivaCompraId;
        $validated['iva_venta_id'] = $ivaVentaId;
        $validated['precio_base_compra'] = $precioBaseCompra;
        $validated['precio_base_venta'] = $precioBaseVenta;

        $validated['precio_compra_final'] = round($precioBaseCompra * (1 + ($ivaCompra / 100)), 2);
        $validated['precio_venta_final'] = round($precioBaseVenta * (1 + ($ivaVenta / 100)), 2);
        $validated['activo'] = $request->has('activo') ? $request->boolean('activo') : $producto->activo;

        $producto->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'producto' => $producto->fresh(['categoria', 'proveedor', 'ivaCompra', 'ivaVenta']),
            ]);
        }

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente');
    }

    public function destroy($id)
{
    $producto = Producto::findOrFail($id);

    // ✅ En lugar de eliminar, marca como inactivo
    $producto->update(['activo' => false]);

    return response()->json([
        'success' => true,
        'mensaje' => 'Producto archivado correctamente'
    ]);
}
}
