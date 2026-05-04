<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Producto;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\StockBajoNotification;
use Illuminate\Support\Facades\Notification;

class VentasController extends Controller
{
    public function index()
    {
        $config = Configuracion::first();
        $ventas = Venta::with('usuario', 'detalles.producto')->get();
        $productos = Producto::where('activo', true)->get();
        return view('admin.ventas.index', compact('config', 'ventas', 'productos'));
    }

    public function create()
    {
        $config = Configuracion::first();
        $productos = Producto::where('activo', true)->get();
        return view('admin.ventas.create', compact('config', 'productos'));
    }

    public function store(Request $request)
    {
        try {
            \Log::info('=== INICIO GUARDAR VENTA ===');

            $request->validate([
                'cliente' => 'required|string',
                'cliente_documento' => 'nullable|string',
                'metodo_pago' => 'required|string',
                'estado' => 'required|string',
                'observaciones' => 'nullable|string',
                'productos_json' => 'required|string',
                'subtotal' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
            ]);

            // ✅ VALIDAR PRODUCTOS ANTES DE CREAR VENTA
            $productos = json_decode($request->productos_json, true);

            foreach ($productos as $producto) {
                $prod = Producto::findOrFail($producto['producto_id']);

                // VALIDACIÓN 1: Producto agotado
                if ($prod->stock_actual <= 0) {
                    return back()->withInput()->with('error', "⚠️ El producto '{$prod->nombre}' está agotado. No hay stock disponible.");
                }

                // VALIDACIÓN 2: Sobrepasa stock actual
                if ($producto['cantidad'] > $prod->stock_actual) {
                    return back()->withInput()->with('error', "⚠️ No hay suficiente stock de '{$prod->nombre}'. Stock disponible: {$prod->stock_actual}, solicitado: {$producto['cantidad']}");
                }
            }

            // Generar número de venta automático
            $ultimaVenta = Venta::orderBy('id', 'desc')->first();
            $numero = $ultimaVenta ? (int)substr($ultimaVenta->numero_factura, 2) + 1 : 1;
            $numero_factura = 'V-' . str_pad($numero, 3, '0', STR_PAD_LEFT);

            // Crear la venta
            $datos = $request->all();
            $datos['usuario_id'] = auth()->id();
            $datos['numero_factura'] = $numero_factura;
            $datos['fecha_venta'] = now();

            $venta = Venta::create($datos);

            \Log::info('Venta creada ID: ' . $venta->id . ' Código: ' . $numero_factura);

            // ✅ GUARDAR PRODUCTOS y RESTAR STOCK SIEMPRE (sin importar estado)
            foreach ($productos as $producto) {
                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto['producto_id'],
                    'cantidad' => $producto['cantidad'],
                    'precio_unitario' => $producto['precio_unitario'],
                    'subtotal' => $producto['subtotal']
                ]);

                // ✅ RESTAR STOCK SIEMPRE (creada la venta, el producto se reserva)
                $prod = Producto::findOrFail($producto['producto_id']);
                $prod->decrement('stock_actual', $producto['cantidad']);
                // ✅ NOTIFICAR STOCK BAJO si es necesario
    $this->verificarYNotificarStockBajo($prod);

                // Registrar movimiento de stock
                $this->registrarMovimientoStock(
                    $producto['producto_id'],
                    'salida_venta',
                    $producto['cantidad'],
                    'venta',
                    $venta->id
                );
            }

            \Log::info('Venta guardada exitosamente');

            return redirect()->route('ventas.index')
                            ->with('success', "✅ ¡Venta creada correctamente! Número: {$numero_factura}");

        } catch (\Exception $e) {
            \Log::error('ERROR AL GUARDAR VENTA: ' . $e->getMessage());
            return back()->withInput()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    // ✅ DESTROY: DEVOLVER STOCK
    public function destroy($id)
    {
        try {
            $venta = Venta::with('detalles.producto')->findOrFail($id);

            // ✅ DEVOLVER STOCK DE TODOS LOS PRODUCTOS
            foreach ($venta->detalles as $detalle) {
                $producto = $detalle->producto;
                $producto->increment('stock_actual', $detalle->cantidad);

                // Registrar movimiento de devolución
                $this->registrarMovimientoStock(
                    $detalle->producto_id,
                    'devolucion_venta',  // Tipo devolución
                    $detalle->cantidad,
                    'venta',
                    $venta->id
                );

                \Log::info("Stock devuelto: Producto {$producto->nombre}, +{$detalle->cantidad}");
            }

            $venta->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '✅ ¡Venta eliminada correctamente y stock devuelto!'
                ]);
            }

            return redirect()->route('ventas.index')
                           ->with('success', '✅ ¡Venta eliminada correctamente y stock devuelto!');

        } catch (\Exception $e) {
            \Log::error('ERROR AL ELIMINAR VENTA: ' . $e->getMessage());

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la venta: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error al eliminar la venta');
        }
    }

    // ✅ CAMBIAR ESTADO (SOLO CAMBIA ESTADO, NO AFECTA STOCK)
    public function cambiarEstado(Request $request, $id)
    {
        try {
            $venta = Venta::with('detalles.producto')->findOrFail($id);
            $nuevoEstado = $request->input('estado');

            // ✅ SOLO CAMBIAR ESTADO (el stock ya se restó al crear)
            $venta->estado = $nuevoEstado;
            $venta->save();

            return response()->json([
                'success' => true,
                'message' => '✅ Venta actualizada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // ✅ REGISTRAR MOVIMIENTO DE STOCK
    private function registrarMovimientoStock($productoId, $tipo, $cantidad, $referenciaTipo, $referenciaId)
    {
        $stockActual = DB::table('movimientos_stock')
            ->where('producto_id', $productoId)
            ->sum(DB::raw("CASE WHEN tipo IN ('entrada_compra', 'devolucion_venta', 'inventario_inicial') THEN cantidad ELSE -cantidad END"));

        // Si es salida, resta; si es devolución, suma
        $stockNuevo = ($tipo === 'devolucion_venta') 
            ? $stockActual + $cantidad 
            : $stockActual - $cantidad;

        DB::table('movimientos_stock')->insert([
            'producto_id' => $productoId,
            'tipo' => $tipo,
            'cantidad' => $cantidad,
            'stock_anterior' => $stockActual,
            'stock_nuevo' => $stockNuevo,
            'referencia_tipo' => $referenciaTipo,
            'referencia_id' => $referenciaId,
            'usuario_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \Log::info("Movimiento stock registrado: Producto {$productoId}, Tipo {$tipo}, Cantidad {$cantidad}");
    }

    // 📈 VENTAS POR MES
public function ventasPorMes()
{
    $data = DB::table('ventas')
        ->selectRaw("DATE_FORMAT(fecha_venta, '%Y-%m') as mes, SUM(total) as total")
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

    return response()->json($data);
}

// 📦 TOP PRODUCTOS
public function topProductos()
{
    $data = DB::table('ventas_detalle as vd')
        ->join('productos as p', 'p.id', '=', 'vd.producto_id')
        ->select('p.nombre', DB::raw('SUM(vd.cantidad) as total'))
        ->groupBy('p.nombre')
        ->orderByDesc('total')
        ->limit(5)
        ->get();

    return response()->json($data);
}

// 🔄 MOVIMIENTOS DE STOCK
public function movimientosStock()
{
    $data = DB::table('movimientos_stock')
        ->selectRaw("
            DATE(created_at) as fecha,
            SUM(CASE WHEN tipo = 'entrada_compra' THEN cantidad ELSE 0 END) as entradas,
            SUM(CASE WHEN tipo = 'salida_venta' THEN cantidad ELSE 0 END) as salidas
        ")
        ->groupBy('fecha')
        ->orderBy('fecha')
        ->get();

    return response()->json($data);
}
// Agrega este método a tu VentasController.php

// 📊 DEVOLUCIONES POR PRODUCTO (para gráfica apilada)
public function devolucionesVsVentas()
{
    // Obtener ventas por producto
    $ventasData = DB::table('ventas_detalle as vd')
        ->join('productos as p', 'p.id', '=', 'vd.producto_id')
        ->select(
            'p.id as producto_id',
            'p.nombre',
            DB::raw('SUM(vd.cantidad) as total_vendido')
        )
        ->groupBy('p.id', 'p.nombre')
        ->get();

    // Obtener devoluciones por producto
    $devolucionesData = DB::table('devoluciones_detalle as dd')
        ->join('productos as p', 'p.id', '=', 'dd.producto_id')
        ->join('devolucion_ventas as dv', 'dv.id', '=', 'dd.devolucion_venta_id')
        ->where('dv.estado', 'completada')  // Solo devoluciones completadas que afectan stock
        ->select(
            'p.id as producto_id',
            'p.nombre',
            DB::raw('SUM(dd.cantidad) as total_devuelto')
        )
        ->groupBy('p.id', 'p.nombre')
        ->get();

    // Combinar datos
    $resultado = [];
    $productosIds = $ventasData->pluck('producto_id')->merge($devolucionesData->pluck('producto_id'))->unique();

    foreach ($productosIds as $id) {
        $venta = $ventasData->firstWhere('producto_id', $id);
        $devolucion = $devolucionesData->firstWhere('producto_id', $id);
        
        $resultado[] = [
            'nombre' => $venta->nombre ?? $devolucion->nombre,
            'vendido' => $venta->total_vendido ?? 0,
            'devuelto' => $devolucion->total_devuelto ?? 0,
            'neto' => ($venta->total_vendido ?? 0) - ($devolucion->total_devuelto ?? 0)
        ];
    }

    // Ordenar por más vendido
    usort($resultado, function($a, $b) {
        return $b['vendido'] - $a['vendido'];
    });

    // Limitar a top 8 productos
    $resultado = array_slice($resultado, 0, 8);

    return response()->json($resultado);
}

// 📈 VENTAS vs DEVOLUCIONES por MES (línea comparativa)
public function ventasVsDevolucionesMensual()
{
    // Ventas por mes
    $ventasMensual = DB::table('ventas')
        ->selectRaw("
            DATE_FORMAT(fecha_venta, '%Y-%m') as mes,
            SUM(total) as total_ventas,
            COUNT(*) as cantidad_ventas
        ")
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

    // Devoluciones por mes
    $devolucionesMensual = DB::table('devolucion_ventas')
        ->where('estado', 'completada')
        ->selectRaw("
            DATE_FORMAT(fecha, '%Y-%m') as mes,
            SUM(total_devuelto) as total_devuelto,
            COUNT(*) as cantidad_devoluciones
        ")
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

    // Combinar datos
    $todosLosMeses = $ventasMensual->pluck('mes')->merge($devolucionesMensual->pluck('mes'))->unique()->sort();

    $resultado = [];
    foreach ($todosLosMeses as $mes) {
        $venta = $ventasMensual->firstWhere('mes', $mes);
        $devolucion = $devolucionesMensual->firstWhere('mes', $mes);
        
        $resultado[] = [
            'mes' => $mes,
            'ventas' => $venta ? floatval($venta->total_ventas) : 0,
            'devoluciones' => $devolucion ? floatval($devolucion->total_devuelto) : 0
        ];
    }

    return response()->json($resultado);
}
}