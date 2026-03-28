<?php
// app/Models/Producto.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'marca',
        'modelo',
        'categoria_id',
        'proveedor_id',
        'precio_compra',
        'precio_venta',
        'stock_minimo',
        'stock_maximo',
        'ubicacion',
        'imagen',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Relaciones principales
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    // Relaciones con detalles (usando modelos pivote)
    public function comprasDetalle()
    {
        return $this->hasMany(CompraDetalle::class);
    }

    public function ventasDetalle()
    {
        return $this->hasMany(VentaDetalle::class);
    }

    public function devolucionesDetalle()
    {
        return $this->hasMany(DevolucionDetalle::class);
    }

    // Relaciones many-to-many directas
    public function compras()
    {
        return $this->belongsToMany(Compra::class, 'compras_detalle')
                    ->using(CompraDetalle::class)
                    ->withPivot('cantidad', 'precio_unitario', 'subtotal');
    }

    public function ventas()
    {
        return $this->belongsToMany(Venta::class, 'ventas_detalle')
                    ->using(VentaDetalle::class)
                    ->withPivot('cantidad', 'precio_unitario', 'subtotal');
    }

    // Movimientos de stock
    public function movimientosStock()
    {
        return $this->hasMany(MovimientoStock::class);
    }

    public function alertas()
    {
        return $this->hasMany(Alerta::class);
    }

    // =============================================
    // ATRIBUTOS CALCULADOS
    // =============================================
    public function getStockActualAttribute()
    {
        $entradas = $this->movimientosStock()
            ->whereIn('tipo', ['entrada_compra', 'devolucion_venta', 'inventario_inicial'])
            ->sum('cantidad');
        
        $salidas = $this->movimientosStock()
            ->whereIn('tipo', ['salida_venta', 'devolucion_compra', 'ajuste'])
            ->sum('cantidad');
        
        return $entradas - $salidas;
    }

    public function getEstadoStockAttribute()
    {
        $stock = $this->stock_actual;
        if ($stock <= 0) return 'agotado';
        if ($stock <= $this->stock_minimo) return 'bajo';
        if ($stock >= $this->stock_maximo) return 'exceso';
        return 'normal';
    }

    // =============================================
    // MÉTODOS ÚTILES
    // =============================================
    public function verificarStockBajo()
    {
        if ($this->stock_actual <= $this->stock_minimo) {
            Alerta::create([
                'producto_id' => $this->id,
                'tipo' => 'stock_bajo',
                'mensaje' => "Stock bajo de {$this->nombre}: {$this->stock_actual} unidades (mínimo {$this->stock_minimo})"
            ]);
        }
    }
}