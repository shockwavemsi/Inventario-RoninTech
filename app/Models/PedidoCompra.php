<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PedidoCompra extends Model
{
    use SoftDeletes;

    protected $table = 'pedidos_compra';

    protected $fillable = [
        'numero_pedido',
        'proveedor_id',
        'fecha_pedido',
        'fecha_entrega_esperada',
        'estado',
        'subtotal',
        'descuento_porcentaje', 
        'descuento_cantidad', 
        'impuesto_total',
        'total',
        'observaciones',
        'usuario_id',
    ];

    // ✅ RELACIONES EXISTENTES
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function lineas()
    {
        return $this->hasMany(PedidoCompraLinea::class, 'pedido_compra_id');
    }

    // ✅ Relación con Albaranes
    public function albaranes()
    {
        return $this->hasMany(AlbaranCompra::class, 'pedido_compra_id');
    }
}