<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Proveedor;        
use App\Models\Categoria;    
use App\Models\Configuracion;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
public function index()
{
    $config = Configuracion::first();
    $productos = Producto::with('categoria', 'proveedor')->get();
    $categorias = Categoria::all();
    $proveedores = Proveedor::all();
    
    // Verifica que stock_actual está llegando
    foreach ($productos as $p) {
        \Log::info("Producto: {$p->nombre}, Stock: {$p->stock_actual}");
    }
    
    return view('admin.productos', compact('config', 'productos', 'categorias', 'proveedores'));
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