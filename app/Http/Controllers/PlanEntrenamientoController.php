<?php

namespace App\Http\Controllers;

use App\Models\PlanEntrenamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanEntrenamientoController extends Controller
{
    public function index()
    {
        $idCiclista = Auth::user()->id;

        $planes = PlanEntrenamiento::where('id_ciclista', $idCiclista)->get();

        return response()->json($planes);
    }

    public function mostrarPlanes()
    {
        return view('menu.planEntrenamiento');
    }

    public function destroyPlanEntrenamiento($id)
    {
        $sesion = PlanEntrenamiento::findOrFail($id);
        $sesion->delete();

        return response()->json(['message' => 'Plan eliminada correctamente']);
    }
    public function update(Request $request, $id)
    {
        $plan = PlanEntrenamiento::findOrFail($id);

        $plan->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'duracion' => $request->duracion
        ]);

        return response()->json([
            'message' => 'Plan actualizado correctamente',
            'plan' => $plan
        ]);
    }

    public function create()
    {
        return view('menu.planCrear');
    }
    public function store(Request $request)
    {
        PlanEntrenamiento::create([
            'id_ciclista' => Auth::id(),
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'objetivo' => $request->objetivo,
            'activo' => $request->activo
        ]);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Plan actualizado correctamente',
            ]);
        }

        return redirect()->route('plan.crear')
            ->with('success', 'Plan creado correctamente');
    }
    public function show(string $id)
    {
    }
    public function edit(string $id)
    {
    }
    public function destroy(string $id)
    {
    }
}