<?php

namespace App\Http\Controllers;

use App\Models\SesionEntrenamiento;
use App\Models\Entrenamiento;
use App\Models\Bicicleta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResultadoController extends Controller
{
    /**
     * Muestra el listado de resultados del ciclista autenticado
     */
    public function mostrarResultados()
    {
        $userId = auth()->id();

        $resultados = Entrenamiento::with(['bicicleta', 'sesion'])
            ->where('id_ciclista', $userId)
            ->orderBy('fecha', 'desc')
            ->get();

        return view('menu.resultadosEntrenamiento', compact('resultados'));
    }

    /**
     * Muestra el formulario para crear un nuevo resultado
     */
    public function create()
    {
        $bicicletas = Bicicleta::all();

        $sesiones = SesionEntrenamiento::whereHas('plan', function ($q) {
                $q->where('id_ciclista', auth()->id());
            })
            ->orderBy('fecha', 'desc')
            ->get();

        return view('form.crearResultadoEntrenamiento', compact('bicicletas', 'sesiones'));
    }

    /**
     * Guarda los datos del nuevo resultado (POST)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_sesion' => 'required|exists:sesion_entrenamientos,id',
            'id_bicicleta' => 'required|exists:bicicleta,id',
            'fecha' => 'required|date',
            'duracion' => 'required|date_format:H:i',
            'kilometros' => 'required|numeric|min:0',
            'recorrido' => 'nullable|string|max:150',
            'pulso_medio' => 'nullable|integer',
            'pulso_max' => 'nullable|integer',
            'potencia_media' => 'nullable|integer',
            'potencia_normalizada' => 'required|integer',
            'velocidad_media' => 'nullable|numeric',
            'comentario' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $sesion = SesionEntrenamiento::with('plan')->find($request->id_sesion);
        if ($sesion->plan->id_ciclista != auth()->id()) {
            return redirect()->back()
                ->with('error', 'La sesión seleccionada no es válida')
                ->withInput();
        }

        $entrenamiento = Entrenamiento::create([
            'id_ciclista' => auth()->id(),
            'id_bicicleta' => $request->id_bicicleta,
            'id_sesion' => $request->id_sesion,
            'fecha' => $request->fecha,
            'duracion' => $request->duracion,
            'kilometros' => $request->kilometros,
            'recorrido' => $request->recorrido,
            'pulso_medio' => $request->pulso_medio,
            'pulso_max' => $request->pulso_max,
            'potencia_media' => $request->potencia_media,
            'potencia_normalizada' => $request->potencia_normalizada,
            'velocidad_media' => $request->velocidad_media,
            'comentario' => $request->comentario
        ]);

        return redirect()->route('resultado.lista')
            ->with('success', 'Resultado guardado correctamente');
    }

    /**
     * Muestra un resultado específico (API)
     */
    public function show($id)
    {
        $userId = auth()->id();

        $resultado = Entrenamiento::with(['bicicleta', 'sesion'])
            ->where('id_ciclista', $userId)
            ->find($id);

        if (!$resultado) {
            return response()->json(['error' => 'Resultado no encontrado'], 404);
        }

        return response()->json($resultado);
    }
}
