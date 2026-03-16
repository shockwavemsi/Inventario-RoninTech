<?php

namespace App\Http\Controllers;

use App\Models\SesionEntrenamiento;
use App\Models\PlanEntrenamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SesionEntrenamientoController extends Controller
{
    public function index(Request $request)
    {
        $idCiclista = Auth::id();

        $offset = $request->query('offset', 0);
        $limit = $request->query('limit', 10);

        $planes = PlanEntrenamiento::where('id_ciclista', $idCiclista)->pluck('id');

        $sesiones = SesionEntrenamiento::whereIn('id_plan', $planes)
            ->orderBy('fecha', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return response()->json($sesiones);
    }

    public function destroySesionEntrenamiento($id)
    {
        $sesion = SesionEntrenamiento::findOrFail($id);
        $sesion->delete();

        return response()->json(['message' => 'Sesión eliminada correctamente']);
    }

    public function mostrarSesiones()
    {
        return view('menu.sesionEntrenamiento');
    }

    public function create()
    {
        $planes = PlanEntrenamiento::where('id_ciclista', Auth::id())->get();

        return view('menu.sesionCrear', compact('planes')); //['planes' => $planes]
    }

    public function store(Request $request)
    {
        SesionEntrenamiento::create([
            'id_ciclista' => Auth::id(),
            'id_plan' => $request->id_plan,   
            'nombre' => $request->nombre,
            'fecha' => $request->fecha,
            'descripcion' => $request->descripcion,
            'completada' => $request->completada
        ]);
        if ($request->ajax()) {
            return response()->json([
                'message' => 'Sesion actualizado correctamente',
            ]);
        }

        return redirect()->route('sesion.crear')
            ->with('success', 'Sesion creada correctamente');
    }

    public function show(string $id)
    {
    }
    public function edit(string $id)
    {
    }
    public function update(Request $request, string $id)
    {
    }
}