<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Configuracion;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $config = Configuracion::first();
        $proveedores = Proveedor::all();

        return view('admin.proveedores', compact('config', 'proveedores'));
    }

    public function create()
    {
        $config = Configuracion::first();
        return view('admin.proveedores_crear', compact('config'));
    }

    public function store(Request $request)
    {
        Proveedor::create($request->all());

        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado con éxito');
    }

    public function destroy($id)
{
    Proveedor::findOrFail($id)->delete();

    return response()->json([
        'success' => true
    ]);
}
}

