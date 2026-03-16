<?php

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CiclistaController;
use App\Http\Controllers\BloqueEntrenamientoController;
use App\Http\Controllers\SesionEntrenamientoController;
use App\Http\Controllers\PlanEntrenamientoController;
use App\Http\Controllers\SesionBloqueController;
use App\Http\Controllers\ResultadoController;

Route::get('/', function () {
  $visited = DB::select('select * from places where visited = ?', [1]); 
  $togo = DB::select('select * from places where visited = ?', [0]);

  return view('travel_list', ['visited' => $visited, 'togo' => $togo ] );
  
});
Route::get('login', [CiclistaController::class, 'showLoginForm'])->name('login');
Route::post('login', [CiclistaController::class, 'login']);
Route::post('logout', [CiclistaController::class, 'logout'])->name('logout');

// Dashboard (protegido)
Route::get('/bienvenida', [CiclistaController::class, 'showBienvenida'])->middleware('auth');

Route::get('register', [CiclistaController::class, 'registerForm'])->name('register');

// Procesar registro
Route::post('register', [CiclistaController::class, 'register']);

//Mostrar datos del ciclista
Route::get('/api/ciclista', [CiclistaController::class, 'index']);
Route::get('/ciclista', [CiclistaController::class, 'mostrarDatosCiclista'])->middleware('auth');

Route::get('/api/bloques', [BloqueEntrenamientoController::class, 'index']);
Route::get('/api/bloques-del-ciclista', [BloqueEntrenamientoController::class, 'bloquesDelCiclista'])->middleware('auth');
// Vista
/*
|--------------------------------------------------------------------------
| BLOQUES DE ENTRENAMIENTO
|--------------------------------------------------------------------------
*/
// API
Route::get('/api/bloques', [BloqueEntrenamientoController::class, 'index']);

// Vistas
Route::get('/bloques', [BloqueEntrenamientoController::class, 'mostrarBloques'])
    ->middleware('auth');

Route::get('/bloques/crear', [BloqueEntrenamientoController::class, 'create'])
    ->name('bloques.crear')
    ->middleware('auth');

Route::post('/bloques', [BloqueEntrenamientoController::class, 'store'])
    ->name('bloques.store')
    ->middleware('auth');

Route::delete('/bloque/{id}/eliminar', [BloqueEntrenamientoController::class, 'destroy'])
    ->name('bloque.eliminar')
    ->middleware('auth');
Route::post('/api/bloques/crear-rapido', [BloqueEntrenamientoController::class, 'crearRapido'])
    ->middleware('auth');


// Procesar Sesiones Entrenamientos
Route::get('/api/sesiones', [SesionEntrenamientoController::class, 'index']);
Route::get('/sesion', [SesionEntrenamientoController::class, 'mostrarSesiones'])->middleware('auth');
Route::delete('/sesion/{id}', [SesionEntrenamientoController::class, 'destroySesionEntrenamiento']);
Route::get('/sesiones/crear', [SesionEntrenamientoController::class, 'create'])->middleware('auth');
// Guardar el plan (POST)
Route::post('/sesion/crear', [SesionEntrenamientoController::class, 'store'])->name('sesion.store');

// Procesar Plan Entrenamientos
Route::get('/api/planes', [PlanEntrenamientoController::class, 'index']); 
Route::get('/plan', [PlanEntrenamientoController::class, 'mostrarPlanes'])->middleware('auth');
Route::delete('/plan/{id}', [PlanEntrenamientoController::class, 'destroyPlanEntrenamiento']);
Route::put('/plan/{id}', [PlanEntrenamientoController::class, 'update']);
// Mostrar formulario de creación de plan
Route::get('/planes/crear', [PlanEntrenamientoController::class, 'create'])->middleware('auth');
// Guardar el plan (POST)
Route::post('/plan/crear', [PlanEntrenamientoController::class, 'store'])->name('plan.store');

/*
|--------------------------------------------------------------------------
| RELACIONES SESIÓN ↔ BLOQUE
|--------------------------------------------------------------------------
*/
// Vistas
Route::get('/sesionbloque', [SesionBloqueController::class, 'mostrarSesionBloque'])
    ->middleware('auth');

// Vistas
Route::get('/sesionbloque/crear', [SesionBloqueController::class, 'crearSesionConBloques'])
    ->name('relaciones.crear')  
    ->middleware('auth');

// Guardar relación
Route::post('/sesion-bloque/crear', [SesionBloqueController::class, 'store'])
    ->name('relaciones.store') 
    ->middleware('auth');

// API para obtener sesiones con bloques
Route::get('/api/sesiones-con-bloques', [SesionBloqueController::class, 'sesionesConBloques'])
    ->middleware('auth');

// Borrar la relación de sesion con bloques
Route::delete('/sesion-bloque/{id}', [SesionBloqueController::class, 'destroy'])
    ->name('relaciones.destroy')
    ->middleware('auth');

 /*
|--------------------------------------------------------------------------
| RESULTADOS
|--------------------------------------------------------------------------
*/
// Vista principal de resultados (listado)
Route::get('/resultado', [ResultadoController::class, 'mostrarResultados'])
    ->name('resultado.lista')
    ->middleware('auth');

// Vista del formulario para crear resultado
Route::get('/resultado/crear', [ResultadoController::class, 'create'])
    ->name('resultado.crear')
    ->middleware('auth');

// Guardar un nuevo resultado en la tabla entrenamientos
Route::post('/resultado/crear', [ResultadoController::class, 'store'])
    ->name('resultado.guardar')  // ← Nombre diferente para POST
    ->middleware('auth');

// Ver detalle de un resultado específico
Route::get('/resultado/{id}', [ResultadoController::class, 'show'])->middleware('auth');
