<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\ProveedorController;

// Página inicial (puedes borrarla si no la usas)
Route::get('/', function () {
    $visited = DB::select('select * from places where visited = ?', [1]); 
    $togo = DB::select('select * from places where visited = ?', [0]);

    return view('travel_list', [
        'visited' => $visited,
        'togo' => $togo
    ]);
});

// Login
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Panel admin
Route::middleware(['auth', 'role:admin'])->get('/admin', [AdminController::class, 'index']);

// Panel usuario normal (si lo usas)
Route::middleware(['auth', 'role:user'])->get('/usuario', function () {
    return "Panel de usuario normal";
});

// CONFIGURACIÓN
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('config.index');
    Route::post('/configuracion', [ConfiguracionController::class, 'update'])->name('config.update');
});

// USUARIOS (CRUD)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios/guardar', [UserController::class, 'store'])->name('usuarios.store');
    Route::delete('/usuarios/{id}/eliminar', [UserController::class, 'destroy'])->name('usuarios.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/proveedores', [ProveedorController::class, 'index'])->name('proveedores.index');
    Route::get('/proveedores/crear', [ProveedorController::class, 'create'])->name('proveedores.create');
    Route::post('/proveedores/guardar', [ProveedorController::class, 'store'])->name('proveedores.store');
    Route::delete('/proveedores/{id}/eliminar', [ProveedorController::class, 'destroy'])->name('proveedores.destroy');
});
Route::get('/proveedores/{id}/json', function($id) {
    return \App\Models\Proveedor::findOrFail($id);
});


