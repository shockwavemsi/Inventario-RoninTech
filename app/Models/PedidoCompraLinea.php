<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoCompraLinea extends Model
{
    protected $table = 'pedidos_compra_linea';
    protected $fillable = [
        'pedido_compra_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'descuento_porcentaje',
        'descuento_cantidad',
        'impuesto_porcentaje',
        'impuesto_cantidad',
        'total'
    ];

    public function pedidoCompra()
    {
        return $this->belongsTo(PedidoCompra::class, 'pedido_compra_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}