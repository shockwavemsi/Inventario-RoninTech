<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bicicleta extends Model
{
    protected $table = 'bicicleta';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'tipo',
        'comentario'
    ];
}
