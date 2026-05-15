<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FacturaCompra;
use App\Models\PedidoCompra;
use App\Models\AlbaranCompra;
use App\Services\CarboneService;

class PdfController extends Controller
{
    protected $carbone;

    public function __construct(CarboneService $carbone)
    {
        this−>carbone=carbone;
    }

    /**
     * ========================
     * FACTURAS
     * ========================
     */

    /**
     * Descargar factura en PDF
     * GET /api/pdf/factura/{id}
     */
    public function descargarFactura($factura_id)
    {
        try {
            // Obtener factura con relaciones
            $factura = FacturaCompra::with([
                'proveedor',
                'lineas.producto',
                'metodo_pago'
            ])->findOrFail($factura_id);

            // Preparar datos
            datos=this->prepararDatosFactura($factura);

            // Generar PDF
            nombreArchivo="Factura"​.factura->numero . "_" . now()->timestamp;
            pdfPath=this->carbone->render('factura', datos,nombreArchivo);

            // Descargar
            return response()->download(
                $pdfPath,
                "Factura_" . $factura->numero . ".pdf",
                ['Content-Type' => 'application/pdf']
            );

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Factura no encontrada',
                'status' => 404
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al generar PDF',
                'mensaje' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Preparar datos estructurados para factura
     */
    private function prepararDatosFactura(FacturaCompra $factura)
    {
        lineas=factura->lineas->map(function($linea) {
            precios​ini​va=linea->cantidad * $linea->precio_compra;
            ivaa​mount=precio_sin_iva * ($linea->porcentaje_iva_compra / 100);
            totalc​oni​va=precio_sin_iva + $iva_amount;

            return [
                'concepto' => $linea->producto->nombre,
                'cantidad' => $linea->cantidad,
                'precio_unitario' => number_format($linea->precio_compra, 2, '.', ''),
                'iva' => $linea->porcentaje_iva_compra,
                'subtotal' => number_format($precio_sin_iva, 2, '.', ''),
                'total_con_iva' => number_format($total_con_iva, 2, '.', '')
            ];
        });

        totals​ubtotal=factura->lineas->sum(fn(l)=>l->cantidad * $l->precio_compra);
        totali​va=factura->total_con_iva - $total_subtotal;

        return [
            'numero_factura' => $factura->numero,
            'fecha' => $factura->fecha->format('d/m/Y'),
            'proveedor' => $factura->proveedor->nombre,
            'lineas' => $lineas->toArray(),
            'total_subtotal' => number_format($total_subtotal, 2, '.', ''),
            'total_iva' => number_format($total_iva, 2, '.', ''),
            'total' => number_format($factura->total_con_iva, 2, '.', ''),
            'metodo_pago' => $factura->metodo_pago->nombre ?? 'Pendiente'
        ];
    }

    /**
     * ========================
     * PEDIDOS
     * ========================
     */

    /**
     * Descargar pedido en PDF
     * GET /api/pdf/pedido/{id}
     */
    public function descargarPedido($pedido_id)
    {
        try {
            $pedido = PedidoCompra::with([
                'proveedor',
                'lineas.producto'
            ])->findOrFail($pedido_id);

            datos=this->prepararDatosPedido($pedido);
            nombreArchivo="Pedido"​.pedido->numero . "_" . now()->timestamp;
            pdfPath=this->carbone->render('pedido', datos,nombreArchivo);

            return response()->download(
                $pdfPath,
                "Pedido_" . $pedido->numero . ".pdf",
                ['Content-Type' => 'application/pdf']
            );

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al generar PDF del pedido',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preparar datos estructurados para pedido
     */
    private function prepararDatosPedido(PedidoCompra $pedido)
    {
        lineas=pedido->lineas->map(function($linea) {
            totall​inea=linea->cantidad * $linea->precio_compra;

            return [
                'concepto' => $linea->producto->nombre,
                'cantidad' => $linea->cantidad,
                'precio_unitario' => number_format($linea->precio_compra, 2, '.', ''),
                'total' => number_format($total_linea, 2, '.', '')
            ];
        });

        totalp​edido=pedido->lineas->sum(fn(l)=>l->cantidad * $l->precio_compra);

        return [
            'numero_pedido' => $pedido->numero,
            'fecha' => $pedido->fecha->format('d/m/Y'),
            'proveedor' => $pedido->proveedor->nombre,
            'lineas' => $lineas->toArray(),
            'total' => number_format($total_pedido, 2, '.', '')
        ];
    }

    /**
     * ========================
     * ALBARANES
     * ========================
     */

    /**
     * Descargar albarán en PDF
     * GET /api/pdf/albarani/{id}
     */
    public function descargarAlbarani($albarani_id)
    {
        try {
            $albarani = AlbaranCompra::with([
                'proveedor',
                'lineas.producto',
                'pedido'
            ])->findOrFail($albarani_id);

            datos=this->prepararDatosAlbarani($albarani);
            nombreArchivo="Albarani"​.albarani->numero . "_" . now()->timestamp;
            pdfPath=this->carbone->render('albarani', datos,nombreArchivo);

            return response()->download(
                $pdfPath,
                "Albarani_" . $albarani->numero . ".pdf",
                ['Content-Type' => 'application/pdf']
            );

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al generar PDF del albarán',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preparar datos estructurados para albarán
     */
    private function prepararDatosAlbarani(AlbaranCompra $albarani)
    {
        lineas=albarani->lineas->map(function($linea) {
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
            'fecha' => $albarani->fecha->format('d/m/Y'),
            'proveedor' => $albarani->proveedor->nombre,
            'lineas' => $lineas->toArray(),
            'total_recibido' => $albarani->lineas->sum('cantidad_recibida'),
            'total_faltante' => $albarani->lineas->sum('cantidad_faltante')
        ];
    }
}