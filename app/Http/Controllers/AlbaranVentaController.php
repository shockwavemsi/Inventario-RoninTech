<?php

namespace App\Http\Controllers;

use App\Models\AlbaranVenta;
use App\Models\PedidoVenta;
use Illuminate\Http\Request;

class AlbaranVentaController extends Controller
{
    public function index()
    {
        $albaranes = AlbaranVenta::with('pedidoVenta.cliente', 'usuario')->get();
        return view('ventas.albaranes.index', compact('albaranes'));
    }

    public function create()
    {
        $pedidosAbiertos = PedidoVenta::where('estado', 'abierto')->get();
        return view('ventas.albaranes.create', compact('pedidosAbiertos'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'pedido_venta_id' => 'required|exists:pedidos_venta,id',
                'numero_albaran' => 'required|unique:albaranes_venta',
                'fecha_albaran' => 'required|date'
            ]);

            $albaran = AlbaranVenta::create([
                'numero_albaran' => $request->numero_albaran,
                'pedido_venta_id' => $request->pedido_venta_id,
                'fecha_albaran' => $request->fecha_albaran,
                'estado' => 'pendiente',
                'usuario_id' => auth()->id()
            ]);

            return redirect()->route('albaranes-venta.show', $albaran)->with('success', '✅ Albarán creado');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    public function show(AlbaranVenta $albaranVenta)
    {
        $albaranVenta->load('pedidoVenta', 'usuario', 'lineas.producto');
        return view('ventas.albaranes.show', compact('albaranVenta'));
    }

    public function edit(AlbaranVenta $albaranVenta)
    {
        return view('ventas.albaranes.edit', compact('albaranVenta'));
    }

    public function update(Request $request, AlbaranVenta $albaranVenta)
    {
        try {
            $albaranVenta->update($request->only('fecha_entrega', 'estado'));
            return back()->with('success', '✅ Albarán actualizado');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    public function destroy(AlbaranVenta $albaranVenta)
    {
        try {
            $albaranVenta->delete();
            return redirect()->route('albaranes-venta.index')->with('success', '✅ Albarán eliminado');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }
}