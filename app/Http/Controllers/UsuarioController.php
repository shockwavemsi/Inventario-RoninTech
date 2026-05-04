<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Compra;
use App\Models\DevolucionVenta;
use App\Models\Configuracion;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function index()
    {
        $config = Configuracion::first();

        // ==========================================
        // 1. VENTAS
        // ==========================================
        $ventasHoy = Venta::whereDate('fecha_venta', today())->sum('total');
        $ventasAyer = Venta::whereDate('fecha_venta', today()->subDay())->sum('total');
        $porcentajeVentas = $ventasAyer > 0 ? round((($ventasHoy - $ventasAyer) / $ventasAyer) * 100, 1) : ($ventasHoy > 0 ? 100 : 0);
        $ventasTotales = Venta::sum('total');

        // ==========================================
        // 2. PRODUCTOS
        // ==========================================
        $totalProductos = Producto::where('activo', true)->count();
        $productosAgotados = Producto::where('stock_actual', 0)->where('activo', true)->count();
        $stockCritico = Producto::whereRaw('stock_actual <= stock_minimo')->where('activo', true)->count();

        // ==========================================
        // 3. COMPRAS
        // ==========================================
        $comprasPendientes = Compra::where('estado', 'pendiente')->count();

        // ==========================================
        // 4. DEVOLUCIONES
        // ==========================================
        $devolucionesMes = DevolucionVenta::where('estado', 'completada')
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->sum('total_devuelto');

        // ==========================================
        // 5. VENTAS vs DEVOLUCIONES (7 días)
        // ==========================================
        $ventas7Dias = DB::table('ventas')
            ->select(DB::raw('DATE(fecha_venta) as fecha'), DB::raw('SUM(total) as total'))
            ->where('fecha_venta', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(fecha_venta)'))
            ->orderBy(DB::raw('DATE(fecha_venta)'))
            ->get();
        
        $devoluciones7Dias = DB::table('devolucion_ventas')
            ->select(DB::raw('DATE(fecha) as fecha'), DB::raw('SUM(total_devuelto) as total'))
            ->where('estado', 'completada')
            ->where('fecha', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(fecha)'))
            ->orderBy(DB::raw('DATE(fecha)'))
            ->get();

        // ==========================================
        // 6. TOP 5 PRODUCTOS MÁS VENDIDOS
        // ==========================================
        $topProductos = DB::table('ventas_detalle as vd')
            ->join('ventas as v', 'v.id', '=', 'vd.venta_id')
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
            ->select('nombre', 'precio_venta')
            ->limit(5)
            ->get();
        
        foreach ($margenes as $m) {
            $precioCompraEstimado = $m->precio_venta * 0.6;
            $m->margen = round((($m->precio_venta - $precioCompraEstimado) / $m->precio_venta) * 100);
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
        $ultimasVentas = Venta::with('usuario')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return (object)[
                    'tipo' => 'venta',
                    'icono' => 'bi-cart-check',
                    'color' => 'success',
                    'descripcion' => "Venta #{$item->numero_factura} - $" . number_format($item->total, 2) . " - {$item->cliente}",
                    'tiempo' => $item->created_at->diffForHumans(),
                    'fecha' => $item->created_at,
                    'usuario' => $item->usuario->name ?? 'sistema'
                ];
            });
        
        $ultimasCompras = Compra::with('proveedor', 'usuario')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return (object)[
                    'tipo' => 'compra',
                    'icono' => 'bi-truck',
                    'color' => 'info',
                    'descripcion' => "Compra #{$item->numero_factura} - " . ($item->proveedor->nombre ?? 'N/A'),
                    'tiempo' => $item->created_at->diffForHumans(),
                    'fecha' => $item->created_at,
                    'usuario' => $item->usuario->name ?? 'sistema'
                ];
            });
        
        $ultimasDevoluciones = DevolucionVenta::with('usuario')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return (object)[
                    'tipo' => 'devolucion',
                    'icono' => 'bi-arrow-return-left',
                    'color' => 'warning',
                    'descripcion' => "Devolución #{$item->id} - $" . number_format($item->total_devuelto, 2),
                    'tiempo' => $item->created_at->diffForHumans(),
                    'fecha' => $item->created_at,
                    'usuario' => $item->usuario->name ?? 'sistema'
                ];
            });
        
        $ultimasActividades = collect()
            ->concat($ultimasVentas)
            ->concat($ultimasCompras)
            ->concat($ultimasDevoluciones)
            ->sortByDesc(function($item) {
                return $item->fecha;
            })
            ->take(10);

        // ==========================================
        // RETORNAR VISTA DE USUARIO NORMAL
        // ==========================================
        return view('user.index', compact(
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
            'ultimasActividades'
        ));
    }
}