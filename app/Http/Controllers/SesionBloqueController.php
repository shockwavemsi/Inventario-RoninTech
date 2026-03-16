<?php

namespace App\Http\Controllers;
use App\Models\SesionEntrenamiento;   
use App\Models\BloqueEntrenamiento;   
use App\Models\SesionBloque;
 use App\Models\PlanEntrenamiento;
use Illuminate\Http\Request;

class SesionBloqueController extends Controller
{
    public function index()
    {
        $pivot = SesionBloque::all();
        return response()->json($pivot);
    }

public function sesionesConBloques()
{
    $userId = auth()->id();

    $sesiones = SesionEntrenamiento::with(['bloques' => function ($query) {
            $query->orderBy('pivot_orden');
        }])
        ->whereHas('plan', function ($query) use ($userId) {
            $query->where('id_ciclista', $userId);
        })
        ->orderBy('nombre')
        ->get();

    return response()->json($sesiones);
}

    /**
     * Vista HTML que mostrará los bloques de sesiones
     */
    public function mostrarSesionBloque()
{
    $userId = auth()->id();

    // Obtener todas las sesiones del ciclista con sus bloques
    $sesiones = SesionEntrenamiento::with(['bloques' => function ($query) {
            $query->orderBy('pivot_orden'); // Ordenar por el campo 'orden' de la tabla pivote
        }])
        ->whereHas('plan', function ($query) use ($userId) {
            $query->where('id_ciclista', $userId); // Filtrar por el ciclista autenticado
        })
        ->orderBy('nombre')
        ->get();

    return view('menu.sesionBloqueEntrenamiento', compact('sesiones'));
    }

    /**
 * Vista: Formulario para asignar bloques a una sesión existente
 */
public function crearSesionConBloques()
{
    $userId = auth()->id();

    // Sesiones del usuario
    $sesiones = SesionEntrenamiento::whereHas('plan', function ($q) use ($userId) {
        $q->where('id_ciclista', $userId);
    })->get();

    // Todos los bloques
    $bloques = BloqueEntrenamiento::all();

    // ✅ PLANES del usuario (para crear nueva sesión)
    $planes = PlanEntrenamiento::where('id_ciclista', $userId)->get();

    return view('form.crearSesionBloqueEntrenamiento', compact('sesiones', 'bloques', 'planes'));
}

public function store(Request $request)
{
    // Validar según el tipo de sesión
    if ($request->tipo_sesion === 'nueva') {
        $request->validate([
            'nueva_sesion_nombre' => 'required|string|max:255',
            'nueva_sesion_descripcion' => 'nullable|string',
            'nueva_sesion_id_plan' => 'required|exists:plan_entrenamiento,id',
        ]);

        // Verificar plan
        $plan = PlanEntrenamiento::find($request->nueva_sesion_id_plan);
        if ($plan->id_ciclista != auth()->id()) {
            return redirect()->back()->with('error', 'Plan no válido');
        }

        // Crear sesión
        $sesion = SesionEntrenamiento::create([
            'nombre' => $request->nueva_sesion_nombre,
            'descripcion' => $request->nueva_sesion_descripcion,
            'id_plan' => $request->nueva_sesion_id_plan,
            'fecha' => now(),
        ]);

        $idSesion = $sesion->id;
    } else {
        $request->validate([
            'id_sesion_entrenamiento' => 'required|exists:sesion_entrenamientos,id',
        ]);
        $idSesion = $request->id_sesion_entrenamiento;
    }

    // ✅ VALIDACIÓN CORREGIDA - Usa 'bloques_ids' en lugar de 'bloques'
    $request->validate([
        'bloques_ids' => 'required|array|min:1',
        'bloques_ids.*' => 'exists:bloque_entrenamiento,id',
        'repeticiones' => 'required|integer|min:1',
    ]);

    // Obtener último orden
    $ultimoOrden = SesionBloque::where('id_sesion_entrenamiento', $idSesion)->max('orden');
    $orden = $ultimoOrden ? $ultimoOrden + 1 : 1;

    // Insertar bloques seleccionados
    foreach ($request->bloques_ids as $idBloque) {
        SesionBloque::create([
            'id_sesion_entrenamiento' => $idSesion,
            'id_bloque_entrenamiento' => $idBloque,
            'repeticiones' => $request->repeticiones,
            'orden' => $orden++,
        ]);
    }

    return redirect()->route('relaciones.crear')
        ->with('success', 'Bloques asignados correctamente a la sesión');
}

/**
 * Eliminar una relación sesión-bloque
 */
public function destroy($id)
{
    // Buscar la relación en la tabla pivote
    $relacion = SesionBloque::find($id);

    if (!$relacion) {
        if (request()->wantsJson()) {
            return response()->json(['error' => 'Relación no encontrada'], 404);
        }
        return redirect()->back()->with('error', 'Relación no encontrada');
    }

    // Eliminar la relación
    $relacion->delete();

    if (request()->wantsJson()) {
        return response()->json(['mensaje' => 'Bloque eliminado correctamente de la sesión']);
    }

    return redirect()->back()->with('success', 'Bloque eliminado correctamente de la sesión');
}


}