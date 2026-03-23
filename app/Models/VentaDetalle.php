<?php
// app/Models/VentaDetalle.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    protected $table = 'ventas_detalle';

    protected $fillable = [
        'venta_id',
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
    public function venta()
    {
        return $this->belongsTo(Venta::class);
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

    public function scopeDeVenta($query, $ventaId)
    {
        return $query->where('venta_id', $ventaId);
    }

    public function scopeMasVendidos($query, $limite = 10)
    {
        return $query->select('producto_id', \DB::raw('SUM(cantidad) as total_vendido'))
                    ->groupBy('producto_id')
                    ->orderBy('total_vendido', 'desc')
                    ->limit($limite);
    }
}