<?php

namespace App\Http\Controllers;

use App\Models\PagoFactura;
use App\Models\FacturaCompra;
use App\Models\MetodoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagoFacturaController extends Controller
{
    /**
     * Crear nuevo pago para una factura
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'factura_compra_id' => 'required|exists:facturas_compra,id',
                'metodo_pago_id' => 'required|exists:metodos_pago,id',
                'monto' => 'required|numeric|min:0.01',
                'fecha_pago' => 'required|date',
                'referencia' => 'nullable|string|max:100',
                'detalles' => 'nullable|json',
                'estado' => 'required|in:pendiente,pagado,en_transito',
                'notas' => 'nullable|string',
            ]);

            $factura = FacturaCompra::find($validated['factura_compra_id']);

            if (!$factura) {
                return back()->with('error', '❌ Factura no encontrada');
            }

            // Validar que el monto no exceda lo pendiente
            $montoDisponible = $factura->getTotalPendiente();
            if ($validated['monto'] > $montoDisponible) {
                return back()->withInput()->with('error', "❌ Monto excede lo pendiente: {$montoDisponible}€");
            }

            // Crear pago
            $pago = PagoFactura::create([
                'factura_compra_id' => $validated['factura_compra_id'],
                'metodo_pago_id' => $validated['metodo_pago_id'],
                'monto' => $validated['monto'],
                'fecha_pago' => $validated['fecha_pago'],
                'referencia' => $validated['referencia'],
                'detalles' => $validated['detalles'] ? json_decode($validated['detalles']) : null,
                'estado' => $validated['estado'],
                'usuario_id' => Auth::id(),
                'notas' => $validated['notas'],
            ]);

            // Actualizar estado de factura automáticamente
            $factura->actualizarEstadoAutomatico();

            \Log::info('✅ PAGO CREADO:', [
                'pago_id' => $pago->id,
                'factura_id' => $factura->id,
                'monto' => $validated['monto'],
                'estado' => $validated['estado']
            ]);

            return back()->with('success', "✅ Pago de {$validated['monto']}€ registrado correctamente");

        } catch (\Exception $e) {
            \Log::error('Error al crear pago:', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar estado de un pago (pendiente → en_transito → pagado)
     */
    public function updateEstado(Request $request, PagoFactura $pagoFactura)
    {
        try {
            $validated = $request->validate([
                'estado' => 'required|in:pendiente,pagado,en_transito',
            ]);

            $pagoAnterior = $pagoFactura->estado;
            $pagoFactura->update(['estado' => $validated['estado']]);

            // Actualizar estado de factura
            $pagoFactura->factura->actualizarEstadoAutomatico();

            \Log::info('✅ PAGO ACTUALIZADO:', [
                'pago_id' => $pagoFactura->id,
                'estado_anterior' => $pagoAnterior,
                'estado_nuevo' => $validated['estado']
            ]);

            return response()->json([
                'success' => true,
                'message' => "✅ Pago actualizado a {$validated['estado']}",
                'resumen' => $pagoFactura->factura->getResumenPagos()
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => '❌ Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Eliminar pago
     */
    public function destroy(PagoFactura $pagoFactura)
    {
        try {
            $factura = $pagoFactura->factura;
            $pagoFactura->delete();

            // Actualizar estado de factura
            $factura->actualizarEstadoAutomatico();

            return response()->json(['success' => true, 'message' => '✅ Pago eliminado']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => '❌ Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Obtener pagos de una factura (AJAX)
     */
    public function getByFactura(FacturaCompra $facturaCompra)
    {
        $pagos = $facturaCompra->pagos()->with('metodoPago')->get()->map(fn($p) => [
            'id' => $p->id,
            'metodo' => $p->metodoPago?->nombre ?? '—',
            'monto' => (float) $p->monto,
            'fecha_pago' => $p->fecha_pago?->format('Y-m-d') ?? '—',
            'referencia' => $p->referencia ?? '—',
            'estado' => $p->estado,
            'notas' => $p->notas,
        ]);

        return response()->json($pagos);
    }
}