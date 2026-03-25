<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index()
    {
        // Siempre habrá solo 1 registro
        $config = Configuracion::first();

        return view('admin.configuracion', compact('config'));
    }

    public function update(Request $request)
    {
        $config = Configuracion::first();

        $config->update($request->all());

        return redirect()->back()->with('success', 'Configuración actualizada correctamente');
    }
}

