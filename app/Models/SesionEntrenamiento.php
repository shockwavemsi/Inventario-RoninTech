<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesionEntrenamiento extends Model
{
    protected $table = 'sesion_entrenamientos';

    protected $fillable = [
        'id_plan',
        'fecha',
        'nombre',
        'descripcion',
        'completada'
    ];

    public function plan()
    {
        return $this->belongsTo(PlanEntrenamiento::class, 'id_plan');
    }
public function bloques()
{
    return $this->belongsToMany(
        BloqueEntrenamiento::class,
        'sesion_bloque',
        'id_sesion_entrenamiento',
        'id_bloque_entrenamiento'
    )->withPivot('id', 'orden', 'repeticiones');
}

}

