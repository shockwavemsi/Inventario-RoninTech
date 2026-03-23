<?php
// app/Models/Configuracion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuracion';

    protected $fillable = [
        'nombre_empresa',
        'ruc',
        'telefono',
        'email',
        'direccion',
        'logo',
        'impuesto_porcentaje',
        'moneda',
        'formato_factura'
    ];

    protected $casts = [
        'impuesto_porcentaje' => 'decimal:2',
    ];
}
