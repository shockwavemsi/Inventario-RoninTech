<?php

namespace App\Http\Controllers;

use App\Models\PedidoVenta;
use App\Models\Cliente;
use App\Models\Producto;
use Illuminate\Http\Request;

class PedidoVentaController extends Controller
{
    public function index()
    {
        $pedidos = PedidoVenta::with('cliente', 'usuario', 'lineas.producto')->get();
        return view('ventas.pedidos.index', compact('pedidos'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $productos = Producto::where('activo', true)->get();
        return view('ventas.pedidos.create', compact('clientes', 'productos'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'cliente_id' => 'required|exists:clientes,id',
                'fecha_pedido' => 'required|date',
                'numero_pedido' => 'required|unique:pedidos_venta',
                'total' => 'required|numeric|min:0',
            ]);

            $pedido = PedidoVenta::create([
                'numero_pedido' => $request->numero_pedido,
                'cliente_id' => $request->cliente_id,
                'fecha_pedido' => $request->fecha_pedido,
                'fecha_entrega_esperada' => $request->fecha_entrega_esperada,
                'subtotal' => $request->subtotal,
                'impuesto' => $request->impuesto ?? 0,
                'total' => $request->total,
                'estado' => 'abierto',
                'observaciones' => $request->observaciones,
                'usuario_id' => auth()->id()
            ]);

            return redirect()->route('pedidos-venta.show', $pedido)->with('success', '✅ Pedido creado correctamente');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    public function show(PedidoVenta $pedidoVenta)
    {
        $pedidoVenta->load('cliente', 'usuario', 'lineas.producto');
        return view('ventas.pedidos.show', compact('pedidoVenta'));
    }

    public function edit(PedidoVenta $pedidoVenta)
    {
        $clientes = Cliente::all();
        return view('ventas.pedidos.edit', compact('pedidoVenta', 'clientes'));
    }

    public function update(Request $request, PedidoVenta $pedidoVenta)
    {
        try {
            $pedidoVenta->update($request->only('fecha_pedido', 'fecha_entrega_esperada', 'observaciones'));
            return back()->with('success', '✅ Pedido actualizado correctamente');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    public function destroy(PedidoVenta $pedidoVenta)
    {
        try {
            $pedidoVenta->delete();
            return redirect()->route('pedidos-venta.index')->with('success', '✅ Pedido eliminado');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }
}