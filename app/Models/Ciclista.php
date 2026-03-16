<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // <--- importante

class Ciclista extends Authenticatable
{
    protected $table = 'ciclista';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'password',
        'fecha_nacimiento',
        'peso_base',
        'altura_base'
    ];

    // Opcional si quieres definir la columna de contraseña
    public function getAuthPassword()
    {
        return $this->password;
    }
}