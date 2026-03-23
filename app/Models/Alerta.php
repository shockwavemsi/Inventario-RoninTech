<?php
// app/Models/Alerta.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    protected $fillable = [
        'producto_id',
        'tipo',
        'mensaje',
        'vista',
        'usuario_visto',
        'fecha_visto'
    ];

    protected $casts = [
        'vista' => 'boolean',
        'fecha_visto' => 'datetime',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function usuarioVisto()
    {
        return $this->belongsTo(User::class, 'usuario_visto');
    }

    public function scopeNoLeidas($query)
    {
        return $query->where('vista', false);
    }
}