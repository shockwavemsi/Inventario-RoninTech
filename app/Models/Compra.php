<?php
// app/Models/Compra.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $fillable = [
        'proveedor_id',
        'numero_factura',
        'fecha_pedido',
        'fecha_entrega_esperada',
        'fecha_entrega_real',
        'subtotal',
        'impuesto',
        'total',
        'estado',
        'observaciones',
        'usuario_id'
    ];

    
    protected $attributes = [
        'estado' => 'pendiente',  // Estado por defecto
    ];

    protected $casts = [
        'fecha_pedido' => 'date',
        'fecha_entrega_esperada' => 'date',
        'fecha_entrega_real' => 'date',
    ];

    // Relación con proveedor
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación con detalles (usando modelo CompraDetalle)
    public function detalles()
    {
        return $this->hasMany(CompraDetalle::class);
    }

    // Relación con productos a través de detalles (más elegante)
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'compras_detalle')
                    ->using(CompraDetalle::class)  // Usa el modelo pivote
                    ->withPivot('cantidad', 'precio_unitario', 'subtotal')
                    ->withTimestamps();
    }

    // Relación con devoluciones
    public function devoluciones()
    {
        return $this->hasMany(DevolucionCompra::class);
    }

    // Método para calcular total automáticamente
    public function calcularTotal()
    {
        $this->subtotal = $this->detalles->sum('subtotal');
        $this->impuesto = $this->subtotal * (config('empresa.impuesto', 21) / 100);
        $this->total = $this->subtotal + $this->impuesto;
        $this->save();
    }
}