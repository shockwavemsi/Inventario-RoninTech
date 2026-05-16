<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TablaIva extends Model
{
    protected $table = 'tabla_ivas';

    protected $fillable = [
        'nombre',
        'porcentaje',
        'descripcion',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'porcentaje' => 'decimal:2'
    ];

    // Relaciones
    public function productosCompra()
    {
        return $this->hasMany(Producto::class, 'iva_compra_id');
    }

    public function productosVenta()
    {
        return $this->hasMany(Producto::class, 'iva_venta_id');
    }
}