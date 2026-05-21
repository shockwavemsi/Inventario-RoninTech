<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;

    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'apellido',
        'documento',
        'telefono',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // ✅ RELACIONES

    /**
     * Un cliente puede tener muchas ventas
     */
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'cliente_id');
    }

    // ✅ ACCESOR PARA NOMBRE COMPLETO

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }
}
