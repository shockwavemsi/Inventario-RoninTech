<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Componentes_bicicleta extends Model
{
    protected $table = 'componentes_bicicleta';
    public $timestamps = false;

    protected $fillable = [
        'id_bicicleta',
        'id_tipo_componente',
        'marca',
        'modelo',
        'especificacion',
        'velocidad',
        'posicion',
        'fecha_montaje',
        'fecha_retiro',
        'km_actuales',
        'km_max_recomendado',
        'activo',
        'comentario'
    ];
}
