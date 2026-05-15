<?php

namespace App\Http\Controllers;

use App\Models\DebitoVenta;
use App\Models\FacturaVenta;
use Illuminate\Http\Request;

class DebitoVentaController extends Controller
{
    public function index()
    {
        $debitos = DebitoVenta::with('facturaVenta.albaranVenta.pedidoVenta.cliente', 'usuario')->get();
        return view('ventas.debitos.index', compact('debitos'));
    }

    public function create()
    {
        $facturasPendientes = FacturaVenta::where('estado', 'pendiente')->get();
        return view('ventas.debitos.create', compact('facturasPendientes'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'factura_venta_id' => 'required|exists:facturas_venta,id',
                'numero_debito' => 'required|unique:debitos_venta',
                'monto' => 'required|numeric|min:0'
            ]);

            $debito = DebitoVenta::create([
                'numero_debito' => $request->numero_debito,
                'factura_venta_id' => $request->factura_venta_id,
                'fecha_debito' => now(),
                'monto' => $request->monto,
                'estado' => 'abierto',
                'usuario_id' => auth()->id()
            ]);

            return redirect()->route('debitos-venta.show', $debito)->with('success', '✅ Débito creado');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    public function show(DebitoVenta $debitoVenta)
    {
        $debitoVenta->load('facturaVenta', 'usuario', 'lineas');
        return view('ventas.debitos.show', compact('debitoVenta'));
    }

    public function destroy(DebitoVenta $debitoVenta)
    {
        try {
            $debitoVenta->delete();
            return redirect()->route('debitos-venta.index')->with('success', '✅ Débito eliminado');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }
}