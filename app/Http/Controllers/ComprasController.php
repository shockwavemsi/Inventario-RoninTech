<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Proveedor;
use App\Models\Configuracion;
use Illuminate\Http\Request;

class ComprasController extends Controller
{
    public function index()
    {
        $config = Configuracion::first();
        $compras = Compra::with('proveedor', 'usuario', 'detalles.producto')->get();
        $proveedores = Proveedor::all();
        return view('admin.compras', compact('config', 'compras', 'proveedores'));
    }

    public function store(Request $request)
    {
        Compra::create($request->all());
        return redirect()->route('compras.index')->with('success', '¡Compra creada correctamente!');
    }

    public function destroy($id)
    {
        try {
            $compra = Compra::findOrFail($id);
            $compra->delete();
            
            // Verificar si es una petición AJAX
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '¡Compra eliminada correctamente!'
                ]);
            }
            
            // Para peticiones normales (no AJAX)
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
}