<?php
// app/Models/Venta.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'numero_factura',
        'fecha_venta',
        'cliente',
        'cliente_documento',
        'subtotal',
        'impuesto',
        'total',
        'metodo_pago',
        'estado',
        'usuario_id',
        'observaciones'
    ];

    protected $casts = [
        'fecha_venta' => 'datetime',
    ];

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación con detalles (usando modelo VentaDetalle)
    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class);
    }

    // Relación con productos a través de detalles
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'ventas_detalle')
                    ->using(VentaDetalle::class)
                    ->withPivot('cantidad', 'precio_unitario', 'subtotal')
                    ->withTimestamps();
    }

    // Relación con devoluciones
    public function devoluciones()
    {
        return $this->hasMany(DevolucionVenta::class);
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