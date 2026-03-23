<?php
// app/Models/CompraDetalle.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompraDetalle extends Model
{
    protected $table = 'compras_detalle';

    protected $fillable = [
        'compra_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal'
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    // =============================================
    // RELACIONES
    // =============================================
    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // =============================================
    // ACCESSORS & MUTATORS
    // =============================================
    public function getSubtotalAttribute()
    {
        return $this->cantidad * $this->precio_unitario;
    }

    // =============================================
    // SCOPES
    // =============================================
    public function scopeDeProducto($query, $productoId)
    {
        return $query->where('producto_id', $productoId);
    }

    public function scopeDeCompra($query, $compraId)
    {
        return $query->where('compra_id', $compraId);
    }
}