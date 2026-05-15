<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaVentaLinea extends Model
{
    protected $table = 'facturas_venta_linea';

    protected $fillable = [
        'factura_venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'descuento',
        'impuesto_porcentaje',
        'impuesto_cantidad',
        'total'
    ];

    public function facturaVenta()
    {
        return $this->belongsTo(FacturaVenta::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}