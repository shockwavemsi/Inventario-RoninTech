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
    $productos = Producto::with('proveedor', 'categoria')->get();
    $proveedores = Proveedor::all();
    $categorias = Categoria::all();
    return view('admin.productos', compact('config', 'productos', 'proveedores', 'categorias'));
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
        return redirect()->route('productos.index')->with('success', '!Producto eliminado correctamente!');
    }
}