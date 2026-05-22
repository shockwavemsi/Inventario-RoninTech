<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\FacturaCompra;
use App\Models\PedidoCompra;
use App\Models\Producto;
use App\Models\Configuracion;
use App\Models\DevolucionVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;

class AdminController extends Controller
{
    public function index()
    {
        $config = Configuracion::first();

        // ==========================================
        // 1. VENTAS
        // ==========================================
        $ventasHoy = Venta::whereDate('fecha_venta', today())->sum('total');
        $ventasAyer = Venta::whereDate('fecha_venta', today()->subDay())->sum('total');
        $porcentajeVentas = $ventasAyer > 0
            ? round((($ventasHoy - $ventasAyer) / $ventasAyer) * 100, 1)
            : ($ventasHoy > 0 ? 100 : 0);
        $ventasTotales = Venta::sum('total');

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
        // 4. DEVOLUCIONES
        // ==========================================
        $devolucionesMes = DevolucionVenta::whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->sum('total_devuelto');

        $devoluciones7Dias = DB::table('devolucion_ventas')
            ->select(DB::raw('DATE(fecha) as fecha'), DB::raw('SUM(total_devuelto) as total'))
            ->where('fecha', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(fecha)'))
            ->orderBy(DB::raw('DATE(fecha)'))
            ->get();

        // ==========================================
        // 5. VENTAS ÚLTIMOS 7 DÍAS
        // ==========================================
        $ventas7Dias = DB::table('ventas')
            ->select(DB::raw('DATE(fecha_venta) as fecha'), DB::raw('SUM(total) as total'))
            ->where('fecha_venta', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(fecha_venta)'))
            ->orderBy(DB::raw('DATE(fecha_venta)'))
            ->get();

        // ==========================================
        // 6. TOP 5 PRODUCTOS MÁS VENDIDOS
        // ==========================================
        $topProductos = DB::table('ventas_detalle as vd')
            ->join('productos as p', 'p.id', '=', 'vd.producto_id')
            ->select('p.nombre', DB::raw('SUM(vd.cantidad) as total_vendido'))
            ->groupBy('p.id', 'p.nombre')
            ->orderByRaw('SUM(vd.cantidad) DESC')
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
            $precioCompraEstimado = $m->precio_venta_final * 0.6; // Estimado
            $m->margen = round((($m->precio_venta_final - $precioCompraEstimado) / $m->precio_venta_final) * 100);
        }

        // ==========================================
        // 8. MOVIMIENTOS DE STOCK (si existe la tabla)
        // ==========================================
        try {
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
        } catch (\Exception $e) {
            $movimientosStock = collect(); // Tabla no existe aún
        }

        // ==========================================
        // 9. ÚLTIMAS ACTIVIDADES (VENTAS + COMPRAS + DEVOLUCIONES)
        // ==========================================

        // Últimas 5 ventas
        $ultimasVentas = Venta::with('usuario')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return (object)[
                    'tipo' => 'venta',
                    'icono' => 'bi-cart-check',
                    'color' => 'success',
                    'descripcion' => "Venta #{$item->numero_factura} - €" . number_format($item->total, 2),
                    'tiempo' => $item->created_at ? $item->created_at->diffForHumans() : 'Hace poco',
                    'fecha' => $item->created_at ?? now(),
                    'usuario' => $item->usuario->name ?? 'sistema'
                ];
            });

        // Últimas 5 compras
        $ultimasCompras = FacturaCompra::with('usuario')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return (object)[
                    'tipo' => 'compra',
                    'icono' => 'bi-truck',
                    'color' => 'info',
                    'descripcion' => "Compra #{$item->numero_factura}",
                    'tiempo' => $item->created_at ? $item->created_at->diffForHumans() : 'Hace poco',
                    'fecha' => $item->created_at ?? now(),
                    'usuario' => $item->usuario->name ?? 'sistema'
                ];
            });

        // Últimas 5 devoluciones
        $ultimasDevoluciones = DevolucionVenta::with('usuario', 'venta')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                // Evitamos el error de sintaxis extrayendo primero el valor
                $numeroFactura = $item->venta ? $item->venta->numero_factura : '?';
                return (object)[
                    'tipo' => 'devolucion',
                    'icono' => 'bi-arrow-return-left',
                    'color' => 'warning',
                    'descripcion' => "Devolución de Venta #{$numeroFactura} - €" . number_format($item->total_devuelto, 2),
                    'tiempo' => $item->created_at ? $item->created_at->diffForHumans() : 'Hace poco',
                    'fecha' => $item->created_at ?? now(),
                    'usuario' => $item->usuario->name ?? 'sistema'
                ];
            });

        // Combinar y ordenar por fecha descendente
        $ultimasActividades = $ultimasVentas
            ->concat($ultimasCompras)
            ->concat($ultimasDevoluciones)
            ->sortByDesc('fecha')
            ->take(10);

            $clientes = Venta::select(
        'cliente as nombre',
        'cliente_documento as documento',
        DB::raw('COUNT(*) as ventas_count'),
        DB::raw('SUM(total) as total_comprado'),
        DB::raw('MAX(fecha_venta) as ultima_compra')
    )
    ->whereNotNull('cliente')
    ->where('cliente', '<>', '')
    ->groupBy('cliente', 'cliente_documento')
    ->orderByDesc('ultima_compra')
    ->limit(8)
    ->get();
        // ==========================================
        // 10. RETORNAR VISTA
        // ==========================================
        return view('admin.index', compact(
            'config',
            'ventasHoy',
            'ventasTotales',
            'porcentajeVentas',
            'totalProductos',
            'productosAgotados',
            'comprasPendientes',
            'devolucionesMes',
            'stockCritico',
            'ventas7Dias',
            'devoluciones7Dias',
            'topProductos',
            'margenes',
            'movimientosStock',
            'ultimasActividades',
            'clientes'
        ));
    }
}