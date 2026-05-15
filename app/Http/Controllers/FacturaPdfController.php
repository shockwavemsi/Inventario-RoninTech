<?php

namespace App\Http\Controllers;

use App\Models\FacturaCompra;
use App\Services\CarboneService;

class FacturaPdfController extends Controller
{
    protected $carbone;

    public function __construct(CarboneService $carbone)
    {
        $this->carbone = $carbone;
    }

    public function descargarFactura($id)
{
    try {
        $factura = FacturaCompra::with(['proveedor', 'lineas.producto'])->findOrFail($id);

        // ✅ SIN la estructura d:{}
        $datos = [
            'numero_factura' => $factura->numero_factura ?? 'SIN NÚMERO',
            'fecha' => $factura->fecha_factura ? date('d/m/Y', strtotime($factura->fecha_factura)) : date('d/m/Y'),
            'proveedor' => $factura->proveedor->nombre ?? 'SIN PROVEEDOR',
            'metodo_pago' => 'Transferencia',
            'total' => number_format($factura->total ?? 0, 2, ',', '.')
        ];

        $nombreArchivo = "Factura_" . ($factura->numero_factura ?? 'temporal') . "_" . now()->timestamp;
        $pdfPath = $this->carbone->render('factura', $datos, $nombreArchivo);

        return response()->download($pdfPath, "Factura_{$factura->numero_factura}.pdf", ['Content-Type' => 'application/pdf']);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al generar PDF', 'mensaje' => $e->getMessage()], 500);
    }
}
}