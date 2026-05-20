<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Producto;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PedidoCompraController;
use App\Http\Controllers\AlbaranCompraController;
use App\Http\Controllers\FacturaCompraController;
use App\Http\Controllers\FacturaPdfController;
use App\Http\Controllers\PagoFacturaController;
use App\Http\Controllers\DevolucionesController;
use App\Models\Configuracion;
use App\Models\DevolucionVenta;

Route::get('/clear-all', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    return 'Cache limpiado';
});

// ============ PÁGINA INICIAL ============

Route::get('/', function () {
    $visited = DB::select('select * from places where visited = ?', [1]); 
    $togo = DB::select('select * from places where visited = ?', [0]);
    return view('travel_list', ['visited' => $visited, 'togo' => $togo]);
});

// ============ LOGIN (SIN AUTENTICACIÓN) ============

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// ============ DASHBOARDS (CON AUTENTICACIÓN) ============

Route::middleware(['auth', 'role:admin'])->get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
Route::middleware(['auth', 'role:user'])->get('/usuario', [UsuarioController::class, 'index'])->name('user.dashboard');

// ============================================================
// 1. RUTAS PARA AMBOS ROLES (ADMIN Y USER) - OPERACIONES
// ============================================================

Route::middleware(['auth'])->group(function () {

    // ========== STOCKS ==========
    Route::get('/stocks', [StockController::class, 'index'])->name('stock.index');

    // ========== VENTAS ==========
    Route::prefix('ventas')->group(function () {
        Route::get('/', [VentasController::class, 'index'])->name('ventas.index');
        Route::post('/', [VentasController::class, 'store'])->name('ventas.store');
        Route::delete('/{venta}', [VentasController::class, 'destroy'])->name('ventas.destroy');
        Route::patch('/{venta}/estado', [VentasController::class, 'cambiarEstado'])->name('ventas.estado');
        Route::get('/buscar-productos', [VentasController::class, 'buscarProductos']);
        Route::get('/proximo-numero', [VentasController::class, 'proximoNumero']);
        Route::get('/{venta}/json', function(Venta $venta) {
            return $venta->load('usuario', 'detalles.producto');
        });
    });

    // ========== COMPRAS ==========
    Route::prefix('compras')->group(function () {
        Route::get('/', [PedidoCompraController::class, 'index'])->name('compras.dashboard');
        Route::get('/pedidos', [PedidoCompraController::class, 'index'])->name('pedidos-compra.index');
        Route::post('/pedidos', [PedidoCompraController::class, 'store'])->name('pedidos-compra.store');
        Route::post('/pedidos/{pedidoCompra}/clonar', [PedidoCompraController::class, 'clonar'])->name('pedidos-compra.clonar');
        Route::get('/pedidos/{pedidoCompra}/json', [PedidoCompraController::class, 'showJson']);
        Route::get('/pedidos/{pedidoCompra}/edit', [PedidoCompraController::class, 'edit'])->name('pedidos-compra.edit');
        Route::put('/pedidos/{pedidoCompra}', [PedidoCompraController::class, 'update'])->name('pedidos-compra.update');
        Route::delete('/pedidos/{pedidoCompra}', [PedidoCompraController::class, 'destroy']);
        Route::get('/albaranes', [AlbaranCompraController::class, 'index'])->name('albaranes-compra.index');
        Route::post('/albaranes', [AlbaranCompraController::class, 'store'])->name('albaranes-compra.store');
        Route::put('/albaranes/{albaranCompra}', [AlbaranCompraController::class, 'update']);
        Route::get('/albaranes/{albaranCompra}/json', [AlbaranCompraController::class, 'showJson']);
        Route::delete('/albaranes/{albaranCompra}', [AlbaranCompraController::class, 'destroy']);
        Route::get('/facturas', [FacturaCompraController::class, 'index'])->name('facturas-compra.index');
        Route::post('/facturas', [FacturaCompraController::class, 'store'])->name('facturas-compra.store');
        Route::get('/pdf/factura/{id}', [FacturaPdfController::class, 'descargarFactura']);
        Route::get('/facturas/{facturaCompra}/json', [FacturaCompraController::class, 'showJson']);
        Route::delete('/facturas/{facturaCompra}', [FacturaCompraController::class, 'destroy']);
        Route::post('/pagos-factura', [PagoFacturaController::class, 'store'])->name('pagos-factura.store');
        Route::patch('/pagos-factura/{pagoFactura}/estado', [PagoFacturaController::class, 'updateEstado'])->name('pagos-factura.updateEstado');
        Route::delete('/pagos-factura/{pagoFactura}', [PagoFacturaController::class, 'destroy'])->name('pagos-factura.destroy');
        Route::get('/facturas/{facturaCompra}/pagos', [PagoFacturaController::class, 'getByFactura']);
        Route::get('/api/proveedor/{proveedorId}/dias-vencimiento', [FacturaCompraController::class, 'obtenerDiasVencimiento']);
        Route::get('/api/buscar-albaranes', [FacturaCompraController::class, 'buscarAlbaranes']);
        Route::get('/api/proveedor/{proveedorId}/formas-pago', [FacturaCompraController::class, 'obtenerFormasPago']);
    });

    // ========== DEVOLUCIONES DE VENTA ==========
    Route::get('/devoluciones', [DevolucionesController::class, 'index'])->name('devoluciones.index');
    Route::post('/devoluciones', [DevolucionesController::class, 'store'])->name('devoluciones.store');
    Route::patch('/devoluciones/{id}/estado', [DevolucionesController::class, 'cambiarEstado'])->name('devoluciones.cambiar-estado');
    Route::delete('/devoluciones/{id}', [DevolucionesController::class, 'destroy'])->name('devoluciones.destroy');
    Route::get('/devoluciones/{id}/json', function($id) {
        return DevolucionVenta::with('usuario', 'venta', 'detalles.producto')->findOrFail($id);
    });

    // ========== ENDPOINTS ADICIONALES (JSON) ==========
    Route::get('/api/proveedor/{id}/productos', function($id) {
        return DB::table('productos')
            ->where('proveedor_id', $id)
            ->where('activo', 1)
            ->select('id', 'nombre', 'marca', 'precio_compra_final')
            ->orderBy('nombre')
            ->get();
    });

    Route::get('/api/buscar-pedidos', function() {
        $query = request()->query('q', '');
        $pedidos = DB::table('pedidos_compra')
            ->where('numero_pedido', 'LIKE', "%{$query}%")
            ->whereIn('estado', ['abierto', 'parcial'])
            ->select('id', 'numero_pedido', 'proveedor_id')
            ->limit(10)
            ->get();
        return response()->json($pedidos);
    });

    Route::get('/api/pedidos/{id}/productos-faltantes', function($id) {
        $pedido = \App\Models\PedidoCompra::find($id);
        if (!$pedido) {
            return response()->json(['error' => 'Pedido no encontrado'], 404);
        }

        $lineas = $pedido->lineas;
        $recibidoPorProducto = [];

        foreach ($pedido->albaranes as $albaran) {
            foreach ($albaran->lineas as $lineaAlbaran) {
                $productoId = $lineaAlbaran->producto_id;
                if (!isset($recibidoPorProducto[$productoId])) {
                    $recibidoPorProducto[$productoId] = 0;
                }
                $recibidoPorProducto[$productoId] += (int)$lineaAlbaran->cantidad_recibida;
            }
        }

        $lineasConFaltante = $lineas->map(function($linea) use ($recibidoPorProducto) {
            $recibido = $recibidoPorProducto[$linea->producto_id] ?? 0;
            $cantidadPedida = (int)$linea->cantidad;
            $faltante = $cantidadPedida - $recibido;
            return [
                'id' => $linea->id,
                'producto_id' => $linea->producto_id,
                'producto_nombre' => $linea->producto?->nombre ?? 'Desconocido',
                'cantidad_pedida' => $cantidadPedida,
                'cantidad_recibida' => $recibido,
                'cantidad_faltante' => max(0, $faltante),
            ];
        })->filter(fn($l) => $l['cantidad_faltante'] > 0);

        return response()->json($lineasConFaltante);
    });

    Route::get('/devoluciones/ventas-disponibles', function() {
        $ventasConDevolucion = DevolucionVenta::where('estado', 'completada')
            ->pluck('venta_id')
            ->toArray();

        $ventas = Venta::where('estado', 'completada')
            ->whereNotIn('id', $ventasConDevolucion)
            ->orderBy('fecha_venta', 'desc')
            ->get()
            ->map(fn($v) => [
                'id' => (int) $v->id,
                'numero_factura' => $v->numero_factura,
                'cliente' => $v->cliente,
                'total' => (float) $v->total,
                'fecha' => $v->fecha_venta->format('d/m/Y'),
            ])
            ->toArray();

        return response()->json(['ventas' => $ventas]);
    });

    Route::get('/api/pedidos', [PedidoCompraController::class, 'getAll']);
    Route::get('/api/albaranes', [AlbaranCompraController::class, 'getAll']);
    Route::get('/api/facturas', [FacturaCompraController::class, 'getAll']);

});

// ============================================================
// 2. RUTAS SOLO PARA ADMIN (MANTENIMIENTO)
// ============================================================

Route::middleware(['auth', 'role:admin'])->group(function () {

    // CONFIGURACIÓN
    Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('config.index');
    Route::post('/configuracion', [ConfiguracionController::class, 'update'])->name('config.update');

    // USUARIOS (CRUD)
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios/guardar', [UserController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{id}/edit', [UserController::class, 'edit']);
    Route::put('/usuarios/{id}', [UserController::class, 'update']);
    Route::delete('/usuarios/{id}', [UserController::class, 'destroy'])->name('usuarios.destroy');

    // PROVEEDORES (CRUD)
    Route::get('/proveedores', [ProveedorController::class, 'index'])->name('proveedores.index');
    Route::get('/proveedores/crear', [ProveedorController::class, 'create'])->name('proveedores.create');
    Route::post('/proveedores/guardar', [ProveedorController::class, 'store'])->name('proveedores.store');
    Route::delete('/proveedores/{id}', [ProveedorController::class, 'destroy']);
    Route::get('/proveedores/{id}/json', [ProveedorController::class, 'show']);
    Route::get('/proveedores/{id}/formas-pago', [ProveedorController::class, 'getFormasPago']);
    Route::post('/proveedores/{id}/formas-pago', [ProveedorController::class, 'saveFormasPago']);

    // PRODUCTOS (CRUD)
    Route::get('/productos/{id}/json', [ProductosController::class, 'show'])->name('productos.show');
    Route::get('/productos/modal/lista', [ProductosController::class, 'listaParaModal'])->name('productos.modal-lista');
    Route::get('/productos', [ProductosController::class, 'index'])->name('productos.index');
    Route::get('/productos/crear', [ProductosController::class, 'create'])->name('productos.create');
    Route::post('/productos/guardar', [ProductosController::class, 'store'])->name('productos.store');
    Route::delete('/productos/{id}/eliminar', [ProductosController::class, 'destroy'])->name('productos.destroy');

});

// ============================================================
// 3. RUTAS DE PRUEBA
// ============================================================

Route::middleware(['auth'])->get('/test/pdf-carbone', function() {
    $carbone = new \App\Services\CarboneService();
    $datos = [
        'nFactura' => 'FAC-2026-001',
        'Fecha_factura' => '17/05/2026',
        'CIF' => 'A12345678',
        'dir_empresa' => 'Calle Principal 123, 28001 Madrid',
        'telefono_empresa' => '+34 912 345 678',
        'cliente_nombre' => 'Juan Pérez García',
        'cliente_dni' => '12345678A',
        'cliente_correo' => 'juan.perez@email.com',
        'cliente_telf' => '+34 666 777 888',
        'metodo_pago' => 'Transferencia Bancaria',
        'banco' => 'BBVA',
        'referencia_pago' => 'REF-JPG-001-2026',
        'productos' => [
            [
                'name' => 'Ryzen 5 5600G',
                'cantidad' => 2,
                'precio_unitario' => 150.00,
                'precio_venta' => 300.00
            ],
            [
                'name' => 'Ryzen 7 5700X',
                'cantidad' => 1,
                'precio_unitario' => 350.00,
                'precio_venta' => 350.00
            ]
        ],
        'subTotal' => 650.00,
        'IVA' => 21,
        'cantidad_IVA' => 136.50,
        'total_pagar' => 786.50
    ];

    try {
        $pdf = $carbone->render('factura_venta', $datos, 'test_' . time());
        return response()->download($pdf, 'factura_test.pdf');
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::middleware(['auth'])->post('/generar-pdf-venta', [VentasController::class, 'generarPDF']);
