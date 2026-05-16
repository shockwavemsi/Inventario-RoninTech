<?php

namespace App\Http\Controllers;

use App\Models\AlbaranCompra;
use App\Models\AlbaranCompraLinea;
use App\Models\PedidoCompra;
use App\Models\DebitoCompra;
use App\Models\DebitoCompraLinea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Proveedor;
use App\Models\Producto;

class AlbaranCompraController extends Controller
{
    /**
     * Mostrar lista de albaranes - ✅ RETORNA TODAS LAS VARIABLES NECESARIAS
     */
public function index()
{
    // ✅ CARGAR PEDIDOS DISPONIBLES
    $pedidosRaw = PedidoCompra::with('proveedor', 'lineas.producto', 'albaranes')
        ->where('estado', '!=', 'completo')
        ->get();

    $pedidos = $pedidosRaw->map(fn($p) => [
        'id' => (int) $p->id,
        'numero_pedido' => $p->numero_pedido,
        'proveedor_id' => (int) $p->proveedor_id,
        'lineas' => $p->lineas->map(fn($l) => [
            'producto_id' => (int) $l->producto_id,
            'producto_nombre' => $l->producto?->nombre ?? '—',
            'cantidad' => (int) $l->cantidad,
        ])->toArray(),
    ])->toArray();

    // ✅ CARGAR ALBARANES
    $albaranes = AlbaranCompra::with('proveedor', 'pedidoCompra')
        ->orderBy('id', 'desc')
        ->get()
        ->map(fn($a) => [
            'id' => (int) $a->id,
            'numero_albaran' => $a->numero_albaran,
            'proveedor' => $a->proveedor?->nombre,
        ])->toArray();

    // ✅ CARGAR FACTURAS
    $facturas = \DB::table('facturas_compra')->orderBy('fecha_factura', 'desc')->get();

    // ✅ OTROS DATOS (CRÍTICO)
    $proveedores = Proveedor::all();
    $productos = Producto::all();

    $ultimoPedidoId = PedidoCompra::latest('id')->first()?->id ?? 0;
    $ultimoAlbaranId = AlbaranCompra::latest('id')->first()?->id ?? 0;
    $ultimoFacturaId = \DB::table('facturas_compra')->latest('id')->first()?->id ?? 0;

    return view('admin.compras.index', compact(
        'pedidos',
        'albaranes',
        'facturas',
        'proveedores',
        'productos',
        'ultimoPedidoId',
        'ultimoAlbaranId',
        'ultimoFacturaId'
    ));
}

    /**
     * Obtener albaranes vía AJAX para la API
     */
    public function getAll()
    {
        $albaranes = AlbaranCompra::with('pedidoCompra')
            ->orderBy('fecha_albaran', 'desc')
            ->get()
            ->map(fn($a) => [
                'id' => (int) $a->id,
                'numero_albaran' => $a->numero_albaran ?? 'N/A',
                'pedido' => $a->pedidoCompra?->numero_pedido ?? '—',
                'fecha_albaran' => is_string($a->fecha_albaran) 
                    ? $a->fecha_albaran 
                    : $a->fecha_albaran?->format('Y-m-d') ?? '—',
                'fecha_recepcion' => is_string($a->fecha_recepcion) 
                    ? $a->fecha_recepcion 
                    : $a->fecha_recepcion?->format('Y-m-d') ?? '—',
                'estado' => $a->estado ?? 'recibido',
                'total' => (float) ($a->total ?? 0),
                'type' => 'albaran',
                'created_at' => is_string($a->created_at) 
                    ? $a->created_at 
                    : $a->created_at?->format('Y-m-d') ?? '—'
            ]);

        return response()->json($albaranes);
    }

    /**
     * ✅ CREAR NUEVO ALBARÁN CON LÍNEAS Y DÉBITOS
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'numero_albaran' => 'required|unique:albaranes_compra,numero_albaran',
                'pedido_compra_id' => 'required|exists:pedidos_compra,id',
                'fecha_recepcion' => 'nullable|date_format:Y-m-d',
                'observaciones' => 'nullable|string',
                'producto_id' => 'required|array|min:1',
                'cantidad_pedida' => 'required|array',
                'cantidad_recibida' => 'required|array',
                'cantidad_faltante' => 'required|array',
                'estado' => 'required|array',
            ]);

            foreach ($validated['estado'] as $estado) {
                if (!in_array($estado, ['recibido', 'parcial', 'falta'])) {
                    throw new \Exception('Estado inválido: ' . $estado);
                }
            }

            $pedidoCompra = PedidoCompra::find($validated['pedido_compra_id']);

            if (!$pedidoCompra) {
                return back()->with('error', '❌ Pedido no encontrado');
            }

            \Log::info('ALBARÁN RECIBIDO:', $request->all());

            $cantidadesRecibidas = $request->input('cantidad_recibida');
            $cantidadesPedidas = $request->input('cantidad_pedida');
            $cantidadesTotalesRecibidas = 0;
            $cantidadesTotalesPedidas = 0;

            foreach ($cantidadesPedidas as $index => $cantPedida) {
                $cantPedida = (int)$cantPedida;
                $cantRecibida = (int)$cantidadesRecibidas[$index];

                $cantidadesTotalesPedidas += $cantPedida;
                $cantidadesTotalesRecibidas += $cantRecibida;
            }

            if ($cantidadesTotalesRecibidas == 0) {
                $estadoAlbaran = 'falta';
            } elseif ($cantidadesTotalesRecibidas < $cantidadesTotalesPedidas) {
                $estadoAlbaran = 'parcial';
            } else {
                $estadoAlbaran = 'recibido';
            }

            $albaran = AlbaranCompra::create([
                'numero_albaran' => $validated['numero_albaran'],
                'pedido_compra_id' => $validated['pedido_compra_id'],
                'proveedor_id' => $pedidoCompra->proveedor_id,
                'fecha_albaran' => now()->toDateString(),
                'fecha_recepcion' => $validated['fecha_recepcion'] ?? now()->toDateString(),
                'estado' => $estadoAlbaran,
                'total' => $pedidoCompra->total,
                'observaciones' => $validated['observaciones'] ?? null,
            ]);

            foreach ($request->input('producto_id') as $index => $productoId) {
                $cantidadPedida = (int)$request->input('cantidad_pedida')[$index];
                $cantidadRecibida = (int)$request->input('cantidad_recibida')[$index];
                $cantidadFaltante = (int)$request->input('cantidad_faltante')[$index];
                $estado = $request->input('estado')[$index] ?? 'recibido';

                AlbaranCompraLinea::create([
                    'albaran_compra_id' => $albaran->id,
                    'producto_id' => $productoId,
                    'cantidad_pedida' => $cantidadPedida,
                    'cantidad_recibida' => $cantidadRecibida,
                    'cantidad_faltante' => $cantidadFaltante,
                    'estado' => $estado,
                ]);
            }

            // ✅ ACTUALIZAR ESTADO DEL ALBARÁN SEGÚN CANTIDADES TOTALES
            $totalFaltante = 0;
            $totalRecibido = 0;

            foreach ($albaran->lineas as $linea) {
                $totalFaltante += (int)$linea->cantidad_faltante;
                $totalRecibido += (int)$linea->cantidad_recibida;
            }

            if ($totalFaltante == 0 && $totalRecibido > 0) {
                $albaran->update(['estado' => 'recibido']);
                \Log::info('✅ Albarán COMPLETO');
            } elseif ($totalFaltante > 0 && $totalRecibido > 0) {
                $albaran->update(['estado' => 'parcial']);
                \Log::info('⚠️ Albarán PARCIAL');
            } elseif ($totalRecibido == 0) {
                $albaran->update(['estado' => 'falta']);
                \Log::info('❌ Albarán SIN RECEPCIÓN');
            }

            // ✅ CREAR DÉBITOS SI HAY FALTANTES
            if ($estadoAlbaran === 'falta' || $estadoAlbaran === 'parcial') {
                $productosFaltantes = [];
                foreach ($request->input('producto_id') as $index => $productoId) {
                    $cantidadFaltante = (int)$request->input('cantidad_faltante')[$index];
                    if ($cantidadFaltante > 0) {
                        $productosFaltantes[$productoId] = $cantidadFaltante;
                    }
                }

                if (!empty($productosFaltantes)) {
                    $ultimoDebito = DebitoCompra::max('id') ?? 0;
                    $numeroDebito = 'DBT-' . str_pad($ultimoDebito + 1, 3, '0', STR_PAD_LEFT);

                    $debito = DebitoCompra::create([
                        'numero_debito' => $numeroDebito,
                        'albaran_compra_id' => $albaran->id,
                        'proveedor_id' => $pedidoCompra->proveedor_id,
                        'fecha_debito' => now()->toDateString(),
                        'fecha_vencimiento' => now()->addDays(7)->toDateString(),
                        'estado' => 'abierto',
                        'observaciones' => 'Artículos faltantes en albarán ' . $albaran->numero_albaran,
                    ]);

                    foreach ($productosFaltantes as $productoId => $cantidadFaltante) {
                        DebitoCompraLinea::create([
                            'debito_compra_id' => $debito->id,
                            'producto_id' => $productoId,
                            'cantidad' => $cantidadFaltante,
                            'estado' => 'pendiente',
                        ]);
                    }

                    \Log::info('✅ DÉBITO CREADO:', [
                        'numero' => $numeroDebito,
                        'cantidad_lineas' => count($productosFaltantes)
                    ]);
                }
            }

            // ✅ ACTUALIZAR ESTADO DEL PEDIDO
            $this->actualizarEstadoPedido($validated['pedido_compra_id']);

            \Log::info('ALBARÁN CREADO:', ['id' => $albaran->id, 'numero' => $albaran->numero_albaran, 'estado' => $estadoAlbaran]);

            return redirect()->route('albaranes-compra.index')
                ->with('success', "✅ Albarán {$albaran->numero_albaran} creado correctamente");

        } catch (\Exception $e) {
            \Log::error('Error al crear albarán:', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    public function showJson(AlbaranCompra $albaranCompra)
    {
        try {
            $albaranCompra->load(['pedidoCompra', 'lineas.producto', 'proveedor']);

            $detalles = $albaranCompra->lineas->map(fn($linea) => [
                'id' => $linea->id,
                'producto_id' => $linea->producto_id,
                'producto_nombre' => $linea->producto?->nombre ?? 'Producto desconocido',
                'cantidad_pedida' => (int) $linea->cantidad_pedida,
                'cantidad_recibida' => (int) $linea->cantidad_recibida,
                'cantidad_faltante' => (int) $linea->cantidad_faltante,
                'estado' => $linea->estado ?? 'recibido'
            ]);

            return response()->json([
                'id' => $albaranCompra->id,
                'numero_albaran' => $albaranCompra->numero_albaran,
                'numero_pedido' => $albaranCompra->pedidoCompra?->numero_pedido ?? '—',
                'proveedor' => $albaranCompra->proveedor?->nombre ?? '—',
                'fecha_albaran' => is_string($albaranCompra->fecha_albaran) 
                    ? $albaranCompra->fecha_albaran 
                    : $albaranCompra->fecha_albaran?->format('Y-m-d') ?? '—',
                'fecha_recepcion' => is_string($albaranCompra->fecha_recepcion) 
                    ? $albaranCompra->fecha_recepcion 
                    : $albaranCompra->fecha_recepcion?->format('Y-m-d') ?? '—',
                'estado' => $albaranCompra->estado ?? 'recibido',
                'total' => (float) ($albaranCompra->total ?? 0),
                'observaciones' => $albaranCompra->observaciones ?? null,
                'detalles' => $detalles,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, AlbaranCompra $albaranCompra)
    {
        try {
            $validated = $request->validate([
                'fecha_recepcion' => 'nullable|date_format:Y-m-d',
                'observaciones' => 'nullable|string',
                'cantidad_recibida' => 'nullable|array',
                'estado' => 'nullable|array',
            ]);

            $albaranCompra->update([
                'fecha_recepcion' => $validated['fecha_recepcion'] ?? $albaranCompra->fecha_recepcion,
                'observaciones' => $validated['observaciones'] ?? $albaranCompra->observaciones,
            ]);

            if ($validated['cantidad_recibida'] ?? null) {
                foreach ($validated['cantidad_recibida'] as $index => $cantidadRecibida) {
                    $linea = $albaranCompra->lineas()->skip($index)->first();
                    if ($linea) {
                        $cantidadFaltante = $linea->cantidad_pedida - $cantidadRecibida;

                        if ($cantidadRecibida == 0) {
                            $estado = 'falta';
                        } elseif ($cantidadRecibida < $linea->cantidad_pedida) {
                            $estado = 'parcial';
                        } else {
                            $estado = 'recibido';
                        }

                        $linea->update([
                            'cantidad_recibida' => $cantidadRecibida,
                            'cantidad_faltante' => $cantidadFaltante,
                            'estado' => $estado,
                        ]);
                    }
                }
            }

            return response()->json(['success' => true, 'message' => '✅ Albarán actualizado']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => '❌ Error: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(AlbaranCompra $albaranCompra)
    {
        try {
            $albaranCompra->lineas()->delete();
            DebitoCompra::where('albaran_compra_id', $albaranCompra->id)->delete();
            $albaranCompra->delete();

            return response()->json(['success' => true, 'message' => '✅ Albarán eliminado']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => '❌ Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * ✅ ACTUALIZAR ESTADO DEL PEDIDO SEGÚN CANTIDADES TOTALES RECIBIDAS
     */
    private function actualizarEstadoPedido($pedidoId)
    {
        $pedido = PedidoCompra::find($pedidoId);
        
        if (!$pedido) return;

        // Sumar cantidades del pedido
        $cantidadTotalPedida = (int)$pedido->lineas->sum('cantidad');
        
        // Sumar cantidades recibidas en TODOS los albaranes
        $cantidadTotalRecibida = 0;
        foreach ($pedido->albaranes as $albaran) {
            foreach ($albaran->lineas as $linea) {
                $cantidadTotalRecibida += (int)$linea->cantidad_recibida;
            }
        }

        \Log::info('📊 Actualizando pedido:', [
            'pedido_id' => $pedidoId,
            'cantidad_pedida' => $cantidadTotalPedida,
            'cantidad_recibida' => $cantidadTotalRecibida,
        ]);

        if ($cantidadTotalRecibida >= $cantidadTotalPedida) {
            // ✅ TODO RECIBIDO
            $pedido->update(['estado' => 'completo']);
            \Log::info('✅ Pedido COMPLETO');
            
        } elseif ($cantidadTotalRecibida > 0) {
            // ⚠️ PARCIALMENTE RECIBIDO
            $pedido->update(['estado' => 'parcial']);
            \Log::info('⚠️ Pedido PARCIAL');
            
        } else {
            // ❌ NADA RECIBIDO
            $pedido->update(['estado' => 'abierto']);
            \Log::info('❌ Pedido ABIERTO');
        }
    }
}
