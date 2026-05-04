<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Compra;
use App\Models\DevolucionVenta;
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
        
        // Ventas de hoy
        $ventasHoy = Venta::whereDate('fecha_venta', today())->sum('total');
        
        // Ventas de ayer (para el porcentaje)
        $ventasAyer = Venta::whereDate('fecha_venta', today()->subDay())->sum('total');
        $porcentajeVentas = $ventasAyer > 0 ? round((($ventasHoy - $ventasAyer) / $ventasAyer) * 100, 1) : ($ventasHoy > 0 ? 100 : 0);
        
        // Ventas totales
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
        // 5. GRÁFICA: VENTAS vs DEVOLUCIONES (7 días)
        // ==========================================
        
        $ventas7Dias = Venta::select(
                DB::raw('DATE(fecha_venta) as fecha'),
                DB::raw('SUM(total) as total')
            )
            ->whereDate('fecha_venta', '>=', now()->subDays(7))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();
        
        $devoluciones7Dias = DevolucionVenta::where('estado', 'completada')
            ->whereDate('fecha', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(fecha) as fecha'),
                DB::raw('SUM(total_devuelto) as total')
            )
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // ==========================================
        // 6. TOP 5 PRODUCTOS MÁS VENDIDOS
        // ==========================================
        
        $topProductos = DB::table('ventas_detalle as vd')
            ->join('ventas as v', 'v.id', '=', 'vd.venta_id')
            ->join('productos as p', 'p.id', '=', 'vd.producto_id')
            ->select(
                'p.id',
                'p.nombre',
                DB::raw('SUM(vd.cantidad) as total_vendido')
            )
            ->groupBy('p.id', 'p.nombre')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        // ==========================================
        // 7. MARGEN DE GANANCIA
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
        // 8. MOVIMIENTOS DE STOCK
        // ==========================================
        
        $movimientosStock = DB::table('movimientos_stock')
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->selectRaw("
                DATE(created_at) as fecha,
                SUM(CASE WHEN tipo IN ('entrada_compra', 'devolucion_venta', 'inventario_inicial') THEN cantidad ELSE 0 END) as entradas,
                SUM(CASE WHEN tipo = 'salida_venta' THEN cantidad ELSE 0 END) as salidas
            ")
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // ==========================================
        // 9. ÚLTIMAS ACTIVIDADES
        // ==========================================
        
        $ultimasVentas = Venta::with('usuario')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get()
            ->map(function($item) {
                return (object)[
                    'tipo' => 'venta',
                    'icono' => 'bi-cart-check',
                    'color' => 'success',
                    'descripcion' => "Venta #{$item->numero_factura} - $" . number_format($item->total, 2) . " - {$item->cliente}",
                    'tiempo' => $item->created_at->diffForHumans(),
                    'usuario' => $item->usuario->name ?? 'sistema'
                ];
            });
        
        $ultimasCompras = Compra::with('proveedor')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function($item) {
                return (object)[
                    'tipo' => 'compra',
                    'icono' => 'bi-truck',
                    'color' => 'info',
                    'descripcion' => "Compra #{$item->numero_factura} - {$item->proveedor->nombre}",
                    'tiempo' => $item->created_at->diffForHumans(),
                    'usuario' => $item->usuario->name ?? 'sistema'
                ];
            });
        
        $ultimasDevoluciones = DevolucionVenta::with('usuario', 'venta')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function($item) {
                return (object)[
                    'tipo' => 'devolucion',
                    'icono' => 'bi-arrow-return-left',
                    'color' => 'warning',
                    'descripcion' => "Devolución #{$item->id} - $" . number_format($item->total_devuelto, 2),
                    'tiempo' => $item->created_at->diffForHumans(),
                    'usuario' => $item->usuario->name ?? 'sistema'
                ];
            });
        
        $ultimasActividades = collect()
            ->concat($ultimasVentas)
            ->concat($ultimasCompras)
            ->concat($ultimasDevoluciones)
            ->sortByDesc(function($item) {
                return $item->tiempo;
            })
            ->take(10);

        // ==========================================
        // RETORNAR VISTA
        // ==========================================
        
        return view('admin.dashboard', compact(
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