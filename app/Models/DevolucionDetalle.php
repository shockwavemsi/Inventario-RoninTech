<?php
// app/Models/DevolucionDetalle.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevolucionDetalle extends Model
{
    protected $table = 'devoluciones_detalle';

    protected $fillable = [
        'devolucion_compra_id',
        'devolucion_venta_id',
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
    public function devolucionCompra()
    {
        return $this->belongsTo(DevolucionCompra::class, 'devolucion_compra_id');
    }

    public function devolucionVenta()
    {
        return $this->belongsTo(DevolucionVenta::class, 'devolucion_venta_id');
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

    public function scopeDeDevolucionCompra($query, $devolucionCompraId)
    {
        return $query->where('devolucion_compra_id', $devolucionCompraId);
    }

    public function scopeDeDevolucionVenta($query, $devolucionVentaId)
    {
        return $query->where('devolucion_venta_id', $devolucionVentaId);
    }
}