<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevolucionDetalle extends Model
{
    protected $table = 'devoluciones_detalle';
    protected $fillable = ['devolucion_venta_id', 'producto_id', 'cantidad', 'precio_unitario', 'subtotal'];

    public function devolucion()
    {
        return $this->belongsTo(DevolucionVenta::class, 'devolucion_venta_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}