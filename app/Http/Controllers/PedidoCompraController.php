<?php

namespace App\Http\Controllers;

use App\Models\PedidoCompra;
use App\Models\PedidoCompraLinea;
use App\Models\AlbaranCompra;
use App\Models\Proveedor;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PedidoCompraController extends Controller
{
    public function index()
{
    // ✅ CARGAR PEDIDOS - MOSTRAR TODOS CON ALBARANES
    $pedidosRaw = PedidoCompra::with('proveedor', 'lineas', 'lineas.producto', 'albaranes')
        ->get();

    $pedidos = $pedidosRaw->map(fn($p) => [
        'id' => (int) $p->id,
        'numero_pedido' => $p->numero_pedido,
        'proveedor_id' => (int) $p->proveedor_id,
        'estado' => $p->estado,
        'albaranes_count' => $p->albaranes->count(),
        'lineas' => $p->lineas->map(fn($l) => [
            'producto_id' => (int) $l->producto_id,
            'producto_nombre' => $l->producto?->nombre ?? '—',
            'cantidad' => (int) $l->cantidad,
        ])->toArray(),
    ])->toArray();

    // ✅ ALBARANES
    $albaranes = AlbaranCompra::with('proveedor', 'pedidoCompra')
        ->orderBy('id', 'desc')
        ->get()
        ->map(fn($a) => [
            'id' => (int) $a->id,
            'numero_albaran' => $a->numero_albaran,
            'proveedor' => $a->proveedor?->nombre,
            'numero_pedido' => $a->pedidoCompra?->numero_pedido,
            'estado' => $a->estado,
        ])->toArray();

    // ✅ FACTURAS
    $facturas = \DB::table('facturas_compra')->orderBy('fecha_factura', 'desc')->get();

    // ✅ OTROS DATOS
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

    public function getAll()
    {
        $pedidos = PedidoCompra::with('proveedor')
            ->orderBy('fecha_pedido', 'desc')
            ->get()
            ->map(fn($p) => [
                'id' => (int) $p->id,
                'numero_pedido' => $p->numero_pedido ?? 'N/A',
                'proveedor' => $p->proveedor->nombre ?? '—',
                'fecha_pedido' => is_string($p->fecha_pedido)
                    ? $p->fecha_pedido
                    : $p->fecha_pedido?->format('Y-m-d') ?? '—',
                'estado' => $p->estado ?? 'abierto',
                'total' => (float) ($p->total ?? 0),
                'type' => 'pedido'
            ]);

        return response()->json($pedidos);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'numero_pedido' => 'required|unique:pedidos_compra,numero_pedido',
                'proveedor_id' => 'required|exists:proveedores,id',
                'fecha_pedido' => 'required|date',
                'fecha_entrega_esperada' => 'nullable|date',
                'estado' => 'required|in:abierto,parcial,completo,cancelado',
                'subtotal' => 'required|numeric|min:0',
                'descuento_porcentaje' => 'nullable|numeric|min:0|max:100',
                'descuento_cantidad' => 'nullable|numeric|min:0',
                'total_general' => 'required|numeric|min:0',
                'observaciones' => 'nullable|string',
                'producto_id' => 'required|array|min:1',
                'cantidad' => 'required|array',
                'precio_unitario' => 'required|array',
            ]);



            $pedido = PedidoCompra::create([
                'numero_pedido' => $validated['numero_pedido'],
                'proveedor_id' => $validated['proveedor_id'],
                'usuario_id' => Auth::id(),
                'fecha_pedido' => $validated['fecha_pedido'],
                'fecha_entrega_esperada' => $validated['fecha_entrega_esperada'],
                'estado' => $validated['estado'],
                'subtotal' => $validated['subtotal'],
                'descuento_porcentaje' => $validated['descuento_porcentaje'] ?? 0,
                'descuento_cantidad' => $validated['descuento_cantidad'] ?? 0,
                'total' => $validated['total_general'],
                'observaciones' => $validated['observaciones'],
            ]);


            foreach ($request->input('producto_id') as $index => $productoId) {
                $cantidad = (int)$request->input('cantidad')[$index];
                $precioUnitario = (float)$request->input('precio_unitario')[$index];
                $total = (float)$request->input('total')[$index];

                PedidoCompraLinea::create([
                    'pedido_compra_id' => $pedido->id,
                    'producto_id' => $productoId,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'total' => $total,
                ]);
            }


            return redirect()->route('pedidos-compra.index')
                ->with('success', "✅ Pedido {$pedido->numero_pedido} creado exitosamente");

        } catch (\Exception $e) {
            return back()->withInput()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    public function showJson(PedidoCompra $pedidoCompra)
    {
        try {
            $pedidoCompra->load(['proveedor', 'lineas', 'albaranes.facturas']);

            $detalles = $pedidoCompra->lineas->map(fn($linea) => [
                'id' => $linea->id,
                'cantidad' => (int) $linea->cantidad,
                'producto_nombre' => $linea->producto?->nombre ?? 'Producto desconocido',
                'descripcion' => $linea->producto?->descripcion ?? '—',
                'precio_unitario' => (float) $linea->precio_unitario,
                'precio_por_linea' => (float) ($linea->cantidad * $linea->precio_unitario),
                'total' => (float) $linea->total
            ]);

            $formatoFecha = function($fecha) {
                if (!$fecha) return '—';
                if (is_string($fecha)) return $fecha;
                return $fecha->format('Y-m-d');
            };

            $albaranes = [];
            if ($pedidoCompra->albaranes) {
                $albaranes = $pedidoCompra->albaranes->map(fn($a) => [
                    'id' => $a->id,
                    'numero_albaran' => $a->numero_albaran,
                    'estado' => $a->estado,
                    'fecha_recepcion' => $formatoFecha($a->fecha_recepcion)
                ])->toArray();
            }

            $facturas = [];
            foreach ($pedidoCompra->albaranes as $albaran) {
                foreach ($albaran->facturas as $factura) {
                    $facturas[] = [
                        'id' => $factura->id,
                        'numero_factura' => $factura->numero_factura
                    ];
                }
            }

            return response()->json([
                'id' => $pedidoCompra->id,
                'numero_pedido' => $pedidoCompra->numero_pedido,
                'proveedor' => $pedidoCompra->proveedor?->nombre ?? '—',
                'fecha_pedido' => $formatoFecha($pedidoCompra->fecha_pedido),
                'fecha_entrega_esperada' => $formatoFecha($pedidoCompra->fecha_entrega_esperada),
                'estado' => $pedidoCompra->estado ?? 'abierto',
                'observaciones' => $pedidoCompra->observaciones ?? null,
                'detalles' => $detalles,
                'subtotal_total' => (float) $detalles->sum('precio_por_linea'),
                'descuento_total' => (float) ($pedidoCompra->descuento_cantidad ?? 0),
                'total_final' => (float) $pedidoCompra->total,
                'albaranes_count' => count($albaranes),
                'albaranes' => $albaranes,
                'facturas' => $facturas
            ]);

        } catch (\Exception $e) {
           
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function edit(PedidoCompra $pedidoCompra)
    {
        $proveedores = Proveedor::all();
        return view('admin.compras.pedidos.edit', compact('pedidoCompra', 'proveedores'));
    }

    public function update(Request $request, PedidoCompra $pedidoCompra)
    {
        try {
            $validated = $request->validate([
                'numero_pedido' => 'required|unique:pedidos_compra,numero_pedido,' . $pedidoCompra->id,
                'estado' => 'nullable|in:abierto,parcial,completo,cancelado',
                'fecha_pedido' => 'required|date',
            ]);

            $pedidoCompra->update($validated);

            return back()->with('success', '✅ Actualizado correctamente');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    public function destroy(PedidoCompra $pedidoCompra)
    {
        try {
            $pedidoCompra->delete();
            return response()->json(['success' => true, 'message' => '✅ Eliminado']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => '❌ Error: ' . $e->getMessage()], 500);
        }
    }
}
