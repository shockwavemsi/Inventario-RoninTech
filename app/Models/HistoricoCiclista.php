<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricoCiclista extends Model
{
    protected $table = 'historico_ciclista';

    protected $fillable = [
        'id_ciclista',
        'fecha',
        'peso',
        'ftp',
        'pulso_max',
        'pulso_reposo',
        'potencia_max',
        'grasa_corporal',
        'vo2max',
        'comentario'
    ];

    public $timestamps = false; // tu tabla NO tiene created_at ni updated_at

    public function ciclista()
    {
        return $this->belongsTo(Ciclista::class, 'id_ciclista');
    }
}
