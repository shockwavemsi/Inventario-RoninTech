<?php

namespace App\Http\Controllers;

use App\Models\FacturaVenta;
use App\Models\AlbaranVenta;
use Illuminate\Http\Request;

class FacturaVentaController extends Controller
{
    public function index()
    {
        $facturas = FacturaVenta::with('albaranVenta.pedidoVenta.cliente', 'usuario')->get();
        return view('ventas.facturas.index', compact('facturas'));
    }

    public function create()
    {
        $albaranesRecibidos = AlbaranVenta::where('estado', 'entregado')->get();
        return view('ventas.facturas.create', compact('albaranesRecibidos'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'albaran_venta_id' => 'required|exists:albaranes_venta,id',
                'numero_factura' => 'required|unique:facturas_venta',
                'fecha_factura' => 'required|date',
                'total' => 'required|numeric|min:0'
            ]);

            $factura = FacturaVenta::create([
                'numero_factura' => $request->numero_factura,
                'albaran_venta_id' => $request->albaran_venta_id,
                'fecha_factura' => $request->fecha_factura,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'subtotal' => $request->subtotal,
                'impuesto' => $request->impuesto,
                'total' => $request->total,
                'estado' => 'pendiente',
                'usuario_id' => auth()->id()
            ]);

            return redirect()->route('facturas-venta.show', $factura)->with('success', '✅ Factura creada');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    public function show(FacturaVenta $facturaVenta)
    {
        $facturaVenta->load('albaranVenta', 'usuario', 'lineas.producto');
        return view('ventas.facturas.show', compact('facturaVenta'));
    }

    public function edit(FacturaVenta $facturaVenta)
    {
        return view('ventas.facturas.edit', compact('facturaVenta'));
    }

    public function update(Request $request, FacturaVenta $facturaVenta)
    {
        try {
            $facturaVenta->update([
                'fecha_pago' => $request->fecha_pago,
                'estado' => $request->estado,
                'observaciones' => $request->observaciones
            ]);

            return back()->with('success', '✅ Factura actualizada');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    public function destroy(FacturaVenta $facturaVenta)
    {
        try {
            $facturaVenta->delete();
            return redirect()->route('facturas-venta.index')->with('success', '✅ Factura eliminada');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }
}