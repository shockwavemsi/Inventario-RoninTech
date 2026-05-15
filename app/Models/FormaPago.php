<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    protected $table = 'formas_pago';
    protected $fillable = ['nombre', 'descripcion', 'icono', 'activo'];

    public function formasPagoProveedor()
    {
        return $this->hasMany(FormasPagoProveedor::class, 'forma_pago_id');
    }
}