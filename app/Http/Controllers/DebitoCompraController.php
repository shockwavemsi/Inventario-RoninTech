<?php

namespace App\Http\Controllers;

use App\Models\DebitoCompra;
use App\Models\FacturaCompra;
use Illuminate\Http\Request;

class DebitoCompraController extends Controller
{
    public function index()
    {
        $debitos = DebitoCompra::with('facturaCompra.albaranCompra.pedidoCompra.proveedor', 'usuario')->get();
        return view('compras.debitos.index', compact('debitos'));
    }

    public function create()
    {
        $facturasPendientes = FacturaCompra::where('estado', 'pendiente')->get();
        return view('compras.debitos.create', compact('facturasPendientes'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'factura_compra_id' => 'required|exists:facturas_compra,id',
                'numero_debito' => 'required|unique:debitos_compra',
                'monto' => 'required|numeric|min:0'
            ]);

            $debito = DebitoCompra::create([
                'numero_debito' => $request->numero_debito,
                'factura_compra_id' => $request->factura_compra_id,
                'fecha_debito' => now(),
                'monto' => $request->monto,
                'estado' => 'abierto',
                'usuario_id' => auth()->id()
            ]);

            return redirect()->route('debitos-compra.show', $debito)->with('success', '✅ Débito creado');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    public function show(DebitoCompra $debitoCompra)
    {
        $debitoCompra->load('facturaCompra', 'usuario', 'lineas');
        return view('compras.debitos.show', compact('debitoCompra'));
    }

    public function destroy(DebitoCompra $debitoCompra)
    {
        try {
            $debitoCompra->delete();
            return redirect()->route('debitos-compra.index')->with('success', '✅ Débito eliminado');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }
}