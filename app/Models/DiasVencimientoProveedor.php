<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiasVencimientoProveedor extends Model
{
    protected $table = 'dias_vencimiento_proveedores';

    protected $fillable = [
        'proveedor_id',
        'dias_vencimiento'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
}