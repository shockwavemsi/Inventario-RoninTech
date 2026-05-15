<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacturaVenta extends Model
{
    protected $table = 'facturas_venta';

    protected $fillable = [
        'numero_factura',
        'albaran_venta_id',
        'fecha_factura',
        'fecha_vencimiento',
        'fecha_pago',
        'estado',
        'subtotal',
        'impuesto',
        'total',
        'observaciones',
        'usuario_id'
    ];

    public function albaranVenta()
    {
        return $this->belongsTo(AlbaranVenta::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function lineas()
    {
        return $this->hasMany(FacturaVentaLinea::class);
    }

    public function debitoVenta()
    {
        return $this->hasOne(DebitoVenta::class);
    }
}