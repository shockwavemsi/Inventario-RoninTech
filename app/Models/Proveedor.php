<?php
// app/Models/Proveedor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $fillable = [
        'nombre',
        'ruc',
        'telefono',
        'email',
        'direccion',
        'contacto_nombre',
        'contacto_telefono',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function compras()
    {
        return $this->hasMany(Compra::class);
    }
}