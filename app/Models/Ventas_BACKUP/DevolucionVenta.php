<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevolucionVenta extends Model
{
    protected $table = 'devolucion_ventas';
    protected $fillable = ['venta_id', 'fecha', 'motivo', 'total_devuelto', 'usuario_id', 'estado'];

    // ✅ AGREGAR ESTO:
    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function detalles()
    {
        return $this->hasMany(DevolucionDetalle::class);
    }
}