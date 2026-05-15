<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaCompraLinea extends Model
{
    protected $table = 'facturas_compra_linea';

    protected $fillable = [
        'factura_compra_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'impuesto_porcentaje',
        'impuesto_cantidad',
        'total'
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'float',
        'impuesto_porcentaje' => 'float',
        'impuesto_cantidad' => 'float',
        'total' => 'float',
    ];

    public function facturaCompra()
    {
        return $this->belongsTo(FacturaCompra::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}