<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PedidoVenta extends Model
{
    use SoftDeletes;

    protected $table = 'pedidos_venta';

    protected $fillable = [
        'numero_pedido',
        'cliente_id',
        'fecha_pedido',
        'fecha_entrega_esperada',
        'subtotal',
        'impuesto',
        'total',
        'estado',
        'observaciones',
        'usuario_id'
    ];

    protected $dates = ['deleted_at'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function lineas()
    {
        return $this->hasMany(PedidoVentaLinea::class);
    }

    public function albaranVenta()
    {
        return $this->hasOne(AlbaranVenta::class);
    }
}