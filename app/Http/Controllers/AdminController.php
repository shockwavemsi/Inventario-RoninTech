<?php

namespace App\Http\Controllers;

use App\Models\FacturaVenta;
use App\Models\FacturaCompra;
use App\Models\PedidoCompra;
use App\Models\Producto;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Configuración de la empresa
        $config = Configuracion::first();

        // ==========================================
        // 1. VENTAS
        // ==========================================

        // // Ventas de hoy
        // $ventasHoy = FacturaVenta::whereDate('fecha_factura', today())->sum('total');

        // // Ventas de ayer (para el porcentaje)
        // $ventasAyer = FacturaVenta::whereDate('fecha_factura', today()->subDay())->sum('total');
        // $porcentajeVentas = $ventasAyer > 0 ? round((($ventasHoy - $ventasAyer) / $ventasAyer) * 100, 1) : ($ventasHoy > 0 ? 100 : 0);

        // // Ventas totales
        // $ventasTotales = FacturaVenta::sum('total');

        // ==========================================
        // 2. PRODUCTOS
        // ==========================================

        $totalProductos = Producto::where('activo', true)->count();
        $productosAgotados = Producto::where('stock_actual', 0)->where('activo', true)->count();
        $stockCritico = Producto::whereRaw('stock_actual <= stock_minimo')->where('activo', true)->count();

        // ==========================================
        // 3. COMPRAS PENDIENTES
        // ==========================================

        $comprasPendientes = PedidoCompra::where('estado', 'abierto')->count();

        // ==========================================
        // 4. DEVOLUCIONES (Se elimina esta sección por ahora)
        // ==========================================

        $devolucionesMes = 0; // Placeholder

        // ==========================================
        // 5. VENTAS vs DEVOLUCIONES (7 días)
        // ==========================================

        $ventas7Dias = DB::table('facturas_venta')
            ->select(DB::raw('DATE(fecha_factura) as fecha'), DB::raw('SUM(total) as total'))
            ->where('fecha_factura', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(fecha_factura)'))
            ->orderBy(DB::raw('DATE(fecha_factura)'))
            ->get();

        $devoluciones7Dias = collect(); // Vacío por ahora (sin devoluciones)

        // ==========================================
        // 6. TOP 5 PRODUCTOS MÁS VENDIDOS
        // ==========================================

        $topProductos = DB::table('facturas_venta_linea as fvl')
            ->join('facturas_venta as fv', 'fv.id', '=', 'fvl.factura_venta_id')
            ->join('productos as p', 'p.id', '=', 'fvl.producto_id')
            ->select('p.nombre', DB::raw('SUM(fvl.cantidad) as total_vendido'))
            ->groupBy('p.id', 'p.nombre')
            ->orderByRaw('SUM(fvl.cantidad) DESC')
            ->limit(5)
            ->get();

        // ==========================================
        // 7. MARGEN DE GANANCIA ESTIMADO
        // ==========================================
        $margenes = DB::table('productos')
            ->where('activo', true)
            ->select('nombre', 'precio_venta_final', 'precio_base_venta')
            ->limit(5)
            ->get();

        foreach ($margenes as $m) {
            $precioCompraEstimado = $m->precio_venta_final * 0.6;
            $m->margen = round((($m->precio_venta_final - $precioCompraEstimado) / $m->precio_venta_final) * 100);
        }

        // ==========================================
        // 8. MOVIMIENTOS DE STOCK (7 días)
        // ==========================================

        $movimientosStock = DB::table('movimientos_stock')
            ->select(
                DB::raw('DATE(created_at) as fecha'),
                DB::raw('SUM(CASE WHEN tipo IN ("entrada_compra", "devolucion_venta", "inventario_inicial") THEN cantidad ELSE 0 END) as entradas'),
                DB::raw('SUM(CASE WHEN tipo = "salida_venta" THEN cantidad ELSE 0 END) as salidas')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get();

        // ==========================================
// 9. ÚLTIMAS ACTIVIDADES
// ==========================================

// Últimas facturas de venta
// $ultimasVentas = FacturaVenta::with('usuario')
//     ->orderBy('created_at', 'desc')
//     ->limit(5)
//     ->get()
//     ->map(function($item) {
//         return (object)[
//             'tipo' => 'venta',
//             'icono' => 'bi-cart-check',
//             'color' => 'success',
//             'descripcion' => "Factura #{$item->numero_factura} - €" . number_format($item->total, 2),
//             'tiempo' => $item->created_at ? $item->created_at->diffForHumans() : 'Hace poco',
//             'fecha' => $item->created_at ?? now(),
//             'usuario' => $item->usuario->name ?? 'sistema'
//         ];
//     });

// Últimas facturas de compra
$ultimasCompras = FacturaCompra::with('usuario')
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get()
    ->map(function($item) {
        return (object)[
            'tipo' => 'compra',
            'icono' => 'bi-truck',
            'color' => 'info',
            'descripcion' => "Factura Compra #{$item->numero_factura}",
            'tiempo' => $item->created_at ? $item->created_at->diffForHumans() : 'Hace poco',
            'fecha' => $item->created_at ?? now(),
            'usuario' => $item->usuario->name ?? 'sistema'
        ];
    });

// Combinar y ordenar
$ultimasActividades = collect()
    // ->concat($ultimasVentas)
    ->concat($ultimasCompras)
    ->sortByDesc(function($item) {
        return $item->fecha;
    })
    ->take(10);
        // ==========================================
        // RETORNAR VISTA
        // =======================================2124===

        // return view('admin.index', compact(
        //     'config',
        //     'ventasHoy',
        //     'ventasTotales',
        //     'porcentajeVentas',
        //     'totalProductos',
        //     'productosAgotados',
        //     'comprasPendientes',
        //     'devolucionesMes',
        //     'stockCritico',
        //     'ventas7Dias',
        //     'devoluciones7Dias',
        //     'topProductos',
        //     'margenes',
        //     'movimientosStock',
        //     'ultimasActividades'
        // ));
    }
}