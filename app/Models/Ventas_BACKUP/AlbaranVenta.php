<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlbaranVenta extends Model
{
    use SoftDeletes;

    protected $table = 'albaranes_venta';

    protected $fillable = [
        'numero_albaran',
        'pedido_venta_id',
        'fecha_albaran',
        'fecha_entrega',
        'estado',
        'usuario_id'
    ];

    protected $dates = ['deleted_at'];

    public function pedidoVenta()
    {
        return $this->belongsTo(PedidoVenta::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function lineas()
    {
        return $this->hasMany(AlbaranVentaLinea::class);
    }

    public function facturaVenta()
    {
        return $this->hasOne(FacturaVenta::class);
    }
}