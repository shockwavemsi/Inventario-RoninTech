<?php
// app/Models/DevolucionCompra.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevolucionCompra extends Model
{
    protected $table = 'devoluciones_compras';

    protected $fillable = [
        'compra_id',
        'fecha',
        'motivo',
        'total_devuelto',
        'usuario_id'
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación muchos a muchos con productos a través de devoluciones_detalle
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'devoluciones_detalle', 'devolucion_compra_id')
                    ->withPivot('cantidad', 'precio_unitario', 'subtotal')
                    ->withTimestamps();
    }
}