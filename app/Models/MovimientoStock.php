<?php
// app/Models/MovimientoStock.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoStock extends Model
{
    protected $table = 'movimientos_stock';

    protected $fillable = [
        'producto_id',
        'tipo',
        'cantidad',
        'stock_anterior',
        'stock_nuevo',
        'referencia_tipo',
        'referencia_id',
        'motivo',
        'usuario_id'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}