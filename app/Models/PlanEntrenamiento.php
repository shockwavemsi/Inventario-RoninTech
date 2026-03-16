<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanEntrenamiento extends Model
{
    // Nombre real de la tabla (Laravel intentaría usar "plan_entrenamientos")
    protected $table = 'plan_entrenamiento';

    // La tabla NO tiene timestamps
    public $timestamps = false;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'id_ciclista',
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'objetivo',
        'activo'
    ];

    // Relación con Ciclista
    public function ciclista()
    {
        return $this->belongsTo(Ciclista::class, 'id_ciclista');
    }
}
