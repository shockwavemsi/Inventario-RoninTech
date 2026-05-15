<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlbaranVentaLinea extends Model
{
    protected $table = 'albaranes_venta_linea';

    protected $fillable = [
        'albaran_venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'impuesto_porcentaje',
        'impuesto_cantidad',
        'total'
    ];

    public function albaranVenta()
    {
        return $this->belongsTo(AlbaranVenta::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}