<?php

namespace App\Http\Controllers;
use App\Models\AlbaranCompra;
use App\Models\AlbaranCompraLinea;
use App\Models\FacturaCompra;
use App\Models\Alb;
use Illuminate\Http\Request;

class FacturaCompraController extends Controller
{
    /**
     * Mostrar lista de facturas
     */
    public function index()
{
    try {
        // ✅ CARGAR FACTURAS EXISTENTES
        $facturas = FacturaCompra::with('albaranCompra', 'proveedor')
            ->orderBy('fecha_factura', 'desc')
            ->paginate(15);

        // ✅ CARGAR ALBARANES DESDE VISTA
        $albaranesVista = \DB::table('vw_albaranes_factura')
            ->orderBy('id', 'desc')
            ->get();

        $albaranes = $albaranesVista->map(function($albaran) {
            try {
                $productos = \DB::table('vw_albaranes_productos')
                    ->where('albaran_id', $albaran->id)
                    ->get()
                    ->map(fn($p) => [
                        'id' => (int) $p->producto_id,
                        'nombre' => $p->producto ?? '—',
                        'marca' => $p->marca ?? '—',
                        'modelo' => $p->modelo ?? '—',
                        'cantidad_pedida' => (int) ($p->cantidad_pedida ?? 0),
                        'cantidad_recibida' => (int) ($p->cantidad_recibida ?? 0),
                        'cantidad_faltante' => (int) ($p->cantidad_faltante ?? 0),
                        'estado' => $p->estado ?? 'pendiente',
                        'precio_compra_final' => (float) ($p->precio_compra_final ?? 0),
                        'porcentaje_iva_compra' => (float) ($p->porcentaje_iva_compra ?? 0),
                        'subtotal' => (float) ($p->subtotal ?? 0),
                    ])
                    ->toArray();

                $formasPago = \DB::table('vw_formas_pago_disponibles')
                    ->where('proveedor_id', $albaran->proveedor_id)
                    ->get()
                    ->map(fn($f) => [
                        'relacion_id' => (int) ($f->relacion_id ?? 0),
                        'forma_pago_id' => (int) ($f->forma_pago_id ?? 0),
                        'forma_nombre' => $f->forma_pago ?? '—',
                        'banco_id' => $f->banco_id ? (int) $f->banco_id : null,
                        'banco_nombre' => $f->banco ?? '—',
                        'referencia' => $f->referencia ?? '—',
                        'nombre_banco' => $f->nombre_banco ?? '—',
                        'label_completo' => $f->label_completo ?? '—',
                    ])
                    ->toArray();

                return [
                    'id' => (int) $albaran->id,
                    'numero_albaran' => (string) $albaran->numero_albaran,
                    'proveedor_id' => (int) $albaran->proveedor_id,
                    'proveedor' => (string) $albaran->proveedor,
                    'pedido' => (string) ($albaran->numero_pedido ?? '—'),
                    'total' => (float) $albaran->total,
                    'estado' => (string) $albaran->estado,
                    'fecha_albaran' => (string) $albaran->fecha_albaran,
                    'total_lineas' => (int) ($albaran->total_lineas ?? 0),
                    'lineas_faltantes' => (int) ($albaran->lineas_faltantes ?? 0),
                    'productos' => $productos,
                    'formas_pago' => $formasPago,
                ];
            } catch (\Exception $e) {
                \Log::error("Error mapeo albarán: " . $e->getMessage());
                return null;
            }
        })
        ->filter(fn($item) => $item !== null)
        ->values()
        ->toArray();

        // ✅ IDS PARA AUTO-GENERAR
        $ultimoFacturaId = \DB::table('facturas_compra')->latest('id')->first()->id ?? 0;
        $ultimoAlbaranId = \DB::table('albaranes_compra')->latest('id')->first()->id ?? 0;

        // ✅ PROVEEDORES Y PRODUCTOS (FALTABAN)
        $proveedores = \App\Models\Proveedor::all();
        $productos = \App\Models\Producto::all();

        return view('admin.compras.index', compact(
            'facturas',
            'albaranes',
            'ultimoFacturaId',
            'ultimoAlbaranId',
            'proveedores',     // ✅ AGREGADO
            'productos'        // ✅ AGREGADO
        ));

    } catch (\Exception $e) {
        \Log::error('Error en index facturas:', ['error' => $e->getMessage()]);
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}

public function buscarAlbaranes(Request $request)
{
    $query = $request->query('q', '');

    $albaranes = \DB::table('albaranes_compra')
        ->where('numero_albaran', 'LIKE', "%{$query}%")
        ->whereNotIn('id', function($subquery) {
            $subquery->select('albaran_compra_id')
                ->from('facturas_compra')
                ->whereNull('deleted_at');
        })
        ->select('id', 'numero_albaran', 'proveedor_id', 'pedido_compra_id', 'total')
        ->limit(10)
        ->get();

    // ✅ ENRIQUECER CON DATOS - SIN VISTAS
    $albaranesCompletos = $albaranes->map(function($albaran) {
        // Productos del albarán
        $productos = \DB::table('albaranes_compra_linea')
            ->join('productos', 'albaranes_compra_linea.producto_id', '=', 'productos.id')
            ->where('albaranes_compra_linea.albaran_compra_id', $albaran->id)
            ->select(
                'productos.id as producto_id',
                'productos.nombre as producto',
                'productos.marca',
                'albaranes_compra_linea.cantidad_pedida',
                'albaranes_compra_linea.cantidad_recibida'
            )
            ->get()
            ->map(fn($p) => [
                'id' => (int) $p->producto_id,
                'nombre' => $p->producto ?? '—',
                'marca' => $p->marca ?? '—',
                'cantidad_pedida' => (int) $p->cantidad_pedida,
                'cantidad_recibida' => (int) $p->cantidad_recibida,
            ])->toArray();

        // Formas de pago del proveedor
        $formasPago = \DB::table('formas_pago_proveedor')
            ->join('formas_pago', 'formas_pago_proveedor.forma_pago_id', '=', 'formas_pago.id')
            ->leftJoin('bancos', 'formas_pago_proveedor.banco_id', '=', 'bancos.id')
            ->where('formas_pago_proveedor.proveedor_id', $albaran->proveedor_id)
            ->select(
                'formas_pago_proveedor.id as relacion_id',
                'formas_pago.id as forma_pago_id',
                'formas_pago.nombre as forma_nombre',
                'bancos.id as banco_id',
                'bancos.nombre as banco_nombre',
                'formas_pago_proveedor.referencia'
            )
            ->get()
            ->map(fn($f) => [
                'relacion_id' => (int) $f->relacion_id,
                'forma_pago_id' => (int) $f->forma_pago_id,
                'forma_nombre' => $f->forma_nombre ?? '—',
                'banco_id' => $f->banco_id ? (int) $f->banco_id : null,
                'banco_nombre' => $f->banco_nombre ?? '—',
                'referencia' => $f->referencia ?? '—',
                'nombre_banco' => $f->banco_nombre ?? '—',
                'label_completo' => ($f->forma_nombre ?? '—') . ' (' . ($f->banco_nombre ?? 'N/A') . ')',
            ])->toArray();

        $proveedor = \DB::table('proveedores')->where('id', $albaran->proveedor_id)->first();
        $pedido = \DB::table('pedidos_compra')->where('id', $albaran->pedido_compra_id)->first();

        return [
            'id' => (int) $albaran->id,
            'numero_albaran' => $albaran->numero_albaran,
            'proveedor_id' => (int) $albaran->proveedor_id,
            'proveedor' => $proveedor?->nombre ?? '—',
            'pedido' => $pedido?->numero_pedido ?? '—',
            'total' => (float) $albaran->total,
            'productos' => $productos,
            'formas_pago' => $formasPago,
        ];
    });

    return response()->json($albaranesCompletos);
}    

    /**
     * Obtener facturas vía AJAX para la API
     */
    public function getAll()
    {
        $facturas = FacturaCompra::with('albaranCompra', 'proveedor')
            ->orderBy('fecha_factura', 'desc')
            ->get()
            ->map(fn($f) => [
                'id' => (int) $f->id,
                'numero_factura' => $f->numero_factura ?? 'N/A',
                'proveedor' => $f->proveedor?->nombre ?? '—',
                'albaran' => $f->albaranCompra?->numero_albaran ?? '—',
                'fecha_factura' => is_string($f->fecha_factura)
                    ? $f->fecha_factura
                    : $f->fecha_factura?->format('Y-m-d') ?? '—',
                'fecha_vencimiento' => is_string($f->fecha_vencimiento)
                    ? $f->fecha_vencimiento
                    : $f->fecha_vencimiento?->format('Y-m-d') ?? '—',
                'estado' => $f->estado ?? 'abierta',
                'total' => (float) ($f->total ?? 0),
                'type' => 'factura',
                'created_at' => is_string($f->created_at)
                    ? $f->created_at
                    : $f->created_at?->format('Y-m-d') ?? '—'
            ]);

        return response()->json($facturas);
    }

    /**
     * Obtener detalles completos de una factura en JSON (para modal)
     */
    public function showJson(FacturaCompra $facturaCompra)
    {
        try {
            $facturaCompra->load([
                'albaranCompra.debitoCompra.lineas.producto',
                'proveedor',
                'lineas.producto',
                'pagos.metodoPago'
            ]);

            $formatDate = function($date) {
                if (is_string($date)) return $date;
                return $date ? $date->format('Y-m-d') : '—';
            };

            return response()->json([
                'id' => (int) $facturaCompra->id,
                'numero_factura' => (string) $facturaCompra->numero_factura,
                'proveedor' => $facturaCompra->proveedor?->nombre ?? '—',
                'estado' => (string) ($facturaCompra->estado ?? 'abierta'),
                'fecha_factura' => $formatDate($facturaCompra->fecha_factura),
                'fecha_vencimiento' => $formatDate($facturaCompra->fecha_vencimiento),
                'total' => (float) ($facturaCompra->total ?? 0),
                'observaciones' => (string) ($facturaCompra->observaciones ?? ''),
                'lineas' => $facturaCompra->lineas ? $facturaCompra->lineas->map(fn($l) => [
                    'id' => (int) $l->id,
                    'producto_nombre' => $l->producto?->nombre ?? '—',
                    'cantidad' => (int) ($l->cantidad ?? 0),
                ]) : [],
                'pagos' => $facturaCompra->pagos ? $facturaCompra->pagos->map(fn($p) => [
                    'id' => (int) $p->id,
                    'monto' => (float) ($p->monto ?? 0),
                    'fecha' => $formatDate($p->fecha_pago),
                    'estado' => (string) ($p->estado ?? 'pendiente'),
                ]) : [],
                'albaran_numero' => $facturaCompra->albaranCompra?->numero_albaran ?? '—',
                'albaran_id' => $facturaCompra->albaranCompra?->id,
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en showJson Factura:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Crear nueva factura - ✅ EXTRAE SOLO EL ID DE forma_pago_id
     */
    public function store(Request $request)
    {
        try {
            \Log::info('📥 Datos recibidos:', $request->all());

            $validated = $request->validate([
                'numero_factura' => 'required|unique:facturas_compra',
                'albaran_compra_id' => 'required|exists:albaranes_compra,id',
                'fecha_factura' => 'required|date',
                'fecha_vencimiento' => 'nullable|date',
                'estado' => 'nullable|in:abierta,pagada,vencida',
                'observaciones' => 'nullable|string',
            ]);

            $albaran = \App\Models\AlbaranCompra::find($validated['albaran_compra_id']);
            if (!$albaran) {
                throw new \Exception('Albarán no encontrado');
            }

            $validated['proveedor_id'] = $albaran->proveedor_id;

            $factura = FacturaCompra::create($validated);
            \Log::info('✅ Factura creada:', ['id' => $factura->id, 'proveedor_id' => $albaran->proveedor_id]);

            // ✅ GUARDAR PAGOS - EXTRAYENDO SOLO EL ID
            // ✅ GUARDAR PAGOS - EXTRAER SOLO EL ID
if ($request->has('forma_pago_id') && is_array($request->forma_pago_id)) {
    foreach ($request->forma_pago_id as $idx => $formaIdString) {
        if (empty($formaIdString)) continue;

        // 🔧 SI VIENE CONCATENADO, EXTRAER SOLO EL ID
        $formaId = $formaIdString;
        if (strpos($formaIdString, '|') !== false) {
            $parts = explode('|', $formaIdString);
            $formaId = (int) trim($parts[0]);
        } else {
            $formaId = (int) $formaIdString;
        }

        if ($formaId <= 0) continue;

        \DB::table('pagos_factura')->insert([
            'factura_compra_id' => $factura->id,
            'forma_pago_proveedor_id' => $formaId,
            'monto' => (float) ($request->monto_pago[$idx] ?? 0),
            'fecha_pago' => $request->fecha_pago[$idx] ?? now(),
            'referencia' => $request->referencia_pago[$idx] ?? '',
            'estado' => $request->estado_pago[$idx] ?? 'pendiente',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
            return redirect()->route('facturas-compra.index')
                ->with('success', '✅ Factura creada correctamente');

        } catch (\Exception $e) {
            \Log::error('❌ Error en store:', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(FacturaCompra $facturaCompra)
    {
        try {
            $facturaCompra->delete();
            return response()->json(['success' => true, 'message' => '✅ Factura eliminada']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => '❌ Error: ' . $e->getMessage()], 500);
        }
    }
}
