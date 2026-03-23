<?php
// app/Models/DevolucionVenta.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevolucionVenta extends Model
{
    protected $table = 'devoluciones_ventas';

    protected $fillable = [
        'venta_id',
        'fecha',
        'motivo',
        'total_devuelto',
        'usuario_id'
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function detalles()
    {
        return $this->hasMany(DevolucionDetalle::class, 'devolucion_venta_id');
    }
}