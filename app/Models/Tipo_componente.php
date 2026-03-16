<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo_componente extends Model
{
    protected $table = 'tipo_componente';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion'
    ];
}
