<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoVentaLinea extends Model
{
    protected $table = 'pedidos_venta_linea';

    protected $fillable = [
        'pedido_venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'descuento',
        'impuesto_porcentaje',
        'impuesto_cantidad',
        'total'
    ];

    public function pedidoVenta()
    {
        return $this->belongsTo(PedidoVenta::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}