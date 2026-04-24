<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\DevolucionesController;


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

// Proveedores
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/proveedores', [ProveedorController::class, 'index'])->name('proveedores.index');
    Route::get('/proveedores/crear', [ProveedorController::class, 'create'])->name('proveedores.create');
    Route::post('/proveedores/guardar', [ProveedorController::class, 'store'])->name('proveedores.store');
    Route::delete('/proveedores/{id}/eliminar', [ProveedorController::class, 'destroy'])->name('proveedores.destroy');
});

// Enpoint JSON para proveedores
Route::get('/proveedores/{id}/json', function($id) {
    return \App\Models\Proveedor::findOrFail($id);
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/stocks', [StockController::class, 'index'])->name('stock.index');
});

Route::get('/stocks/{id}/json', function($id) {
    return \App\Models\Producto::with('proveedor', 'categoria')->findOrFail($id);
});

// Productos
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/productos', [ProductosController::class, 'index'])->name('productos.index');
    Route::get('/productos/crear', [ProductosController::class, 'create'])->name('productos.create');
    Route::post('/productos/guardar', [ProductosController::class, 'store'])->name('productos.store');
    Route::delete('/productos/{id}/eliminar', [ProductosController::class, 'destroy'])->name('productos.destroy');
});

// Endpoint JSON para productos
Route::get('/productos/{id}/json', function($id) {
    return \App\Models\Producto::findOrFail($id);
});

Route::get('/productos/{id}/json', function($id) {
    return \App\Models\Producto::with('proveedor', 'categoria')->findOrFail($id);  // ← Agregar with()
});


// Compras
Route::middleware(['auth', 'role:admin'])->group(function () {

    // ✅ RUTAS SIN PARÁMETROS (primero)
    Route::get('/compras', [ComprasController::class, 'index'])->name('compras.index');
    Route::get('/compras/create', [ComprasController::class, 'create'])->name('compras.create');
    Route::post('/compras/store', [ComprasController::class, 'store'])->name('compras.store');
    Route::get('/compras/ultimo-numero', function() {
        return response()->json([
            'numero_factura' => \App\Models\Compra::generarNumeroFactura()
        ]);
    });

    // ✅ RUTAS CON PARÁMETROS ESPECÍFICOS (después)
    Route::get('/compras/{id}/json', function($id) {
        return \App\Models\Compra::with('proveedor', 'usuario', 'detalles.producto')->findOrFail($id);
    });
    Route::delete('/compras/{id}/eliminar', [ComprasController::class, 'destroy'])->name('compras.destroy');
    Route::post('/compras/{id}/estado', [ComprasController::class, 'cambiarEstado'])->name('compras.cambiarEstado');
    Route::patch('/compras/{id}', [ComprasController::class, 'update'])->name('compras.update');
});

// ✅ RUTAS FUERA DEL MIDDLEWARE (rutas públicas o especiales)
Route::get('/api/productos-por-proveedor/{proveedorId}', function($proveedorId) {
    return \App\Models\Producto::where('proveedor_id', $proveedorId)
                                ->where('activo', true)
                                ->get(['id', 'nombre', 'marca', 'modelo', 'precio_compra']);
})->middleware(['auth', 'role:admin']);


// Ventas
// Ventas
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/ventas', [VentasController::class, 'index'])->name('ventas.index');
    Route::get('/ventas/crear', [VentasController::class, 'create'])->name('ventas.create');
    Route::post('/ventas/guardar', [VentasController::class, 'store'])->name('ventas.store');
    Route::delete('/ventas/{id}/eliminar', [VentasController::class, 'destroy'])->name('ventas.destroy');
});

// Endpoint JSON para ventas
Route::get('/ventas/{id}/json', function($id) {
    return \App\Models\Venta::with('detalles.producto')->findOrFail($id);
});

// Cálcula el último número de venta
Route::get('/ventas/proximo-numero', function() {
    $ultimaVenta = \App\Models\Venta::orderBy('id', 'desc')->first();
    $numero = $ultimaVenta ? (int)substr($ultimaVenta->numero_factura, 2) + 1 : 1;
    $numero_factura = 'V-' . str_pad($numero, 3, '0', STR_PAD_LEFT);

    return response()->json(['numero' => $numero_factura]);
});

Route::patch('/ventas/{id}/estado', [VentasController::class, 'cambiarEstado'])->name('ventas.cambiar-estado');

// devoluciones
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/devoluciones', [DevolucionesController::class, 'index'])->name('devoluciones.index');
    Route::get('/devoluciones/crear', [DevolucionesController::class, 'create'])->name('devoluciones.create');
    Route::post('/devoluciones/guardar', [DevolucionesController::class, 'store'])->name('devoluciones.store');
    Route::patch('/devoluciones/{id}/estado', [DevolucionesController::class, 'cambiarEstado'])->name('devoluciones.cambiar-estado');
    Route::delete('/devoluciones/{id}/eliminar', [DevolucionesController::class, 'destroy'])->name('devoluciones.destroy');
});

Route::get('/devoluciones/{id}/json', function($id) {
    return \App\Models\DevolucionVenta::with('usuario', 'venta', 'detalles.producto')->findOrFail($id);
});


