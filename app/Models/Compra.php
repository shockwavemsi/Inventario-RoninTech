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
        'estado' => 'pendiente',
    ];

    protected $casts = [
        'fecha_pedido' => 'date',
        'fecha_entrega_esperada' => 'date',
        'fecha_entrega_real' => 'date',
    ];

    // Generar número de factura automáticamente
    public static function generarNumeroFactura()
    {
        $year = date('Y');
        
        // Buscar la última factura del año actual
        $ultimaFactura = self::whereYear('created_at', $year)
                            ->orderBy('id', 'desc')
                            ->first();
        
        if ($ultimaFactura && preg_match('/FAC-' . $year . '-(\d+)/', $ultimaFactura->numero_factura, $matches)) {
            $ultimoNumero = intval($matches[1]);
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            $nuevoNumero = 1;
        }
        
        // Formatear el número con ceros a la izquierda (ej: 001, 010, 100)
        $numeroFormateado = str_pad($nuevoNumero, 3, '0', STR_PAD_LEFT);
        
        return "FAC-{$year}-{$numeroFormateado}";
    }

    // Evento que se ejecuta antes de crear el registro
    protected static function booted()
    {
        static::creating(function ($compra) {
            if (!$compra->usuario_id) {
                $compra->usuario_id = auth()->id();
            }
            
            // Generar número de factura si no viene en la petición
            if (!$compra->numero_factura) {
                $compra->numero_factura = self::generarNumeroFactura();
            }
        });
    }

    // Resto de tus relaciones...
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function detalles()
    {
        return $this->hasMany(CompraDetalle::class);
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'compras_detalle')
                    ->using(CompraDetalle::class)
                    ->withPivot('cantidad', 'precio_unitario', 'subtotal')
                    ->withTimestamps();
    }

    public function devoluciones()
    {
        return $this->hasMany(DevolucionCompra::class);
    }

    public function calcularTotal()
    {
        $this->subtotal = $this->detalles->sum('subtotal');
        $this->impuesto = $this->subtotal * (config('empresa.impuesto', 21) / 100);
        $this->total = $this->subtotal + $this->impuesto;
        $this->save();
    }
}