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
// app/Http/Controllers/ProveedorController.php

public function edit($id)
{
    $proveedor = Proveedor::findOrFail($id);
    return response()->json($proveedor); // Mismo formato que el JSON actual
}

public function update(Request $request, $id)
{
    $proveedor = Proveedor::findOrFail($id);
    $proveedor->update($request->all());
    
    return response()->json([
        'success' => true,
        'message' => 'Proveedor actualizado correctamente'
    ]);
}
}

