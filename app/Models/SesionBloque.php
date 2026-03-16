<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesionBloque extends Model
{
    protected $table = 'sesion_bloque';
    public $timestamps = false;

    protected $fillable = [
        'id_sesion_entrenamiento',
        'id_bloque_entrenamiento',
        'orden',
        'repeticiones'
    ];
}
