<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DebitoVenta extends Model
{
    use SoftDeletes;

    protected $table = 'debitos_venta';

    protected $fillable = [
        'numero_debito',
        'factura_venta_id',
        'fecha_debito',
        'monto',
        'estado',
        'usuario_id'
    ];

    protected $dates = ['deleted_at'];

    public function facturaVenta()
    {
        return $this->belongsTo(FacturaVenta::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function lineas()
    {
        return $this->hasMany(DebitoVentaLinea::class);
    }
}