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
    $productos = Producto::with('categoria', 'proveedor', 'ivaCompra', 'ivaVenta')
        ->orderBy('id', 'desc')  // ← Nuevos primero
        ->paginate(10);           // ← Paginación de 10
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

    public function destroy($id)
{
    Producto::findOrFail($id)->delete();

    return response()->json([
        'success' => true
    ]);
}
}