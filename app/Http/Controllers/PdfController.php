<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FacturaCompra;
use App\Models\FacturaVenta;
use App\Models\PedidoCompra;
use App\Models\AlbaranCompra;
use App\Services\CarboneService;

class PdfController extends Controller
{
    protected $carbone;

    public function __construct(CarboneService $carbone)
    {
        $this->carbone = $carbone;
    }

    // ============ FACTURAS COMPRA ============
    public function descargarFacturaCompra($factura_id)
    {
        try {
            $factura = FacturaCompra::with([
                'proveedor',
                'lineas.producto',
                'metodo_pago'
            ])->findOrFail($factura_id);

            $datos = $this->prepararDatosFacturaCompra($factura);
            $nombreArchivo = "Factura_Compra_" . $factura->numero . "_" . now()->timestamp;
            $pdfPath = $this->carbone->render('factura_compra', $datos, $nombreArchivo);

            return response()->download(
                $pdfPath,
                "Factura_Compra_{$factura->numero}.pdf",
                ['Content-Type' => 'application/pdf']
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    private function prepararDatosFacturaCompra(FacturaCompra $factura)
    {
        $lineas = $factura->lineas->map(function($linea) {
            $precio_sin_iva = $linea->cantidad * $linea->precio_compra;
            $iva_amount = $precio_sin_iva * ($linea->porcentaje_iva_compra / 100);
            $total_con_iva = $precio_sin_iva + $iva_amount;

            return [
                'concepto' => $linea->producto->nombre,
                'cantidad' => $linea->cantidad,
                'precio_unitario' => number_format($linea->precio_compra, 2, '.', ''),
                'iva' => $linea->porcentaje_iva_compra,
                'subtotal' => number_format($precio_sin_iva, 2, '.', ''),
                'total_con_iva' => number_format($total_con_iva, 2, '.', '')
            ];
        });

        $total_subtotal = $factura->lineas->sum(fn($l) => $l->cantidad * $l->precio_compra);
        $total_iva = ($factura->total_con_iva ?? 0) - $total_subtotal;

        return [
            'numero_factura' => $factura->numero,
            'fecha' => $factura->fecha_factura ? date('d/m/Y', strtotime($factura->fecha_factura)) : date('d/m/Y'),
            'proveedor' => $factura->proveedor->nombre ?? 'SIN PROVEEDOR',
            'lineas' => $lineas->toArray(),
            'total_subtotal' => number_format($total_subtotal, 2, '.', ''),
            'total_iva' => number_format($total_iva, 2, '.', ''),
            'total' => number_format($factura->total_con_iva ?? 0, 2, '.', ''),
            'metodo_pago' => $factura->metodo_pago->nombre ?? 'Pendiente'
        ];
    }

    // ============ FACTURAS VENTA ============
    public function descargarFacturaVenta($factura_id)
    {
        try {
            $factura = FacturaVenta::with([
                'cliente',
                'detalles.producto'
            ])->findOrFail($factura_id);

            $datos = $this->prepararDatosFacturaVenta($factura);
            $nombreArchivo = "Factura_Venta_" . $factura->numero_factura . "_" . now()->timestamp;
            $pdfPath = $this->carbone->render('factura_venta', $datos, $nombreArchivo);

            return response()->download(
                $pdfPath,
                "Factura_Venta_{$factura->numero_factura}.pdf",
                ['Content-Type' => 'application/pdf']
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    private function prepararDatosFacturaVenta(FacturaVenta $factura)
    {
        $detalles = $factura->detalles->map(function($detalle) {
            $subtotal = $detalle->cantidad * $detalle->precio_unitario;
            return [
                'producto' => $detalle->producto->nombre,
                'cantidad' => $detalle->cantidad,
                'precio_unitario' => number_format($detalle->precio_unitario, 2, '.', ''),
                'subtotal' => number_format($subtotal, 2, '.', '')
            ];
        });

        $total_subtotal = $factura->detalles->sum(fn($d) => $d->cantidad * $d->precio_unitario);
        $total_iva = ($factura->total ?? 0) - $total_subtotal;

        return [
            'numero_factura' => $factura->numero_factura,
            'fecha' => $factura->fecha_venta ? date('d/m/Y', strtotime($factura->fecha_venta)) : date('d/m/Y'),
            'cliente' => $factura->cliente ?? 'MOSTRADOR',
            'detalles' => $detalles->toArray(),
            'total_subtotal' => number_format($total_subtotal, 2, '.', ''),
            'total_iva' => number_format($total_iva, 2, '.', ''),
            'total' => number_format($factura->total ?? 0, 2, '.', '')
        ];
    }

    // ============ PEDIDOS ============
    public function descargarPedido($pedido_id)
    {
        try {
            $pedido = PedidoCompra::with([
                'proveedor',
                'lineas.producto'
            ])->findOrFail($pedido_id);

            $datos = $this->prepararDatosPedido($pedido);
            $nombreArchivo = "Pedido_" . $pedido->numero . "_" . now()->timestamp;
            $pdfPath = $this->carbone->render('pedido', $datos, $nombreArchivo);

            return response()->download(
                $pdfPath,
                "Pedido_{$pedido->numero}.pdf",
                ['Content-Type' => 'application/pdf']
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    private function prepararDatosPedido(PedidoCompra $pedido)
    {
        $lineas = $pedido->lineas->map(function($linea) {
            $total_linea = $linea->cantidad * $linea->precio_compra;
            return [
                'concepto' => $linea->producto->nombre,
                'cantidad' => $linea->cantidad,
                'precio_unitario' => number_format($linea->precio_compra, 2, '.', ''),
                'total' => number_format($total_linea, 2, '.', '')
            ];
        });

        $total_pedido = $pedido->lineas->sum(fn($l) => $l->cantidad * $l->precio_compra);

        return [
            'numero_pedido' => $pedido->numero,
            'fecha' => $pedido->fecha ? date('d/m/Y', strtotime($pedido->fecha)) : date('d/m/Y'),
            'proveedor' => $pedido->proveedor->nombre,
            'lineas' => $lineas->toArray(),
            'total' => number_format($total_pedido, 2, '.', '')
        ];
    }

    // ============ ALBARANES ============
    public function descargarAlbarani($albarani_id)
    {
        try {
            $albarani = AlbaranCompra::with([
                'proveedor',
                'lineas.producto',
                'pedido'
            ])->findOrFail($albarani_id);

            $datos = $this->prepararDatosAlbarani($albarani);
            $nombreArchivo = "Albarani_" . $albarani->numero . "_" . now()->timestamp;
            $pdfPath = $this->carbone->render('albarani', $datos, $nombreArchivo);

            return response()->download(
                $pdfPath,
                "Albarani_{$albarani->numero}.pdf",
                ['Content-Type' => 'application/pdf']
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    private function prepararDatosAlbarani(AlbaranCompra $albarani)
    {
        $lineas = $albarani->lineas->map(function($linea) {
            return [
                'concepto' => $linea->producto->nombre,
                'pedida' => $linea->cantidad_pedida,
                'recibida' => $linea->cantidad_recibida,
                'faltante' => $linea->cantidad_faltante,
                'estado' => $linea->estado
            ];
        });

        return [
            'numero_albarani' => $albarani->numero,
            'numero_pedido' => $albarani->pedido->numero ?? 'N/A',
            'fecha' => $albarani->fecha ? date('d/m/Y', strtotime($albarani->fecha)) : date('d/m/Y'),
            'proveedor' => $albarani->proveedor->nombre,
            'lineas' => $lineas->toArray(),
            'total_recibido' => $albarani->lineas->sum('cantidad_recibida'),
            'total_faltante' => $albarani->lineas->sum('cantidad_faltante')
        ];
    }
}