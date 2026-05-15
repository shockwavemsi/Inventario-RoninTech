<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    protected $fillable = ['nombre', 'codigo_banco', 'pais'];

    public function formasPagoProveedor()
    {
        return $this->hasMany(FormasPagoProveedor::class, 'banco_id');
    }
}