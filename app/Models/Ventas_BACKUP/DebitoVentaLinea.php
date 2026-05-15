<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DebitoVentaLinea extends Model
{
    protected $table = 'debitos_venta_linea';

    protected $fillable = [
        'debito_venta_id',
        'concepto',
        'monto'
    ];

    public function debitoVenta()
    {
        return $this->belongsTo(DebitoVenta::class);
    }
}