<?php

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
        'precio_base_compra',
        'iva_compra_id',
        'precio_compra_final',
        'precio_base_venta',
        'iva_venta_id',
        'precio_venta_final',
        'stock_actual',
        'stock_minimo',
        'stock_maximo',
        'ubicacion',
        'imagen',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'precio_base_compra' => 'decimal:2',
        'precio_compra_final' => 'decimal:2',
        'precio_base_venta' => 'decimal:2',
        'precio_venta_final' => 'decimal:2',
    ];

    // =============================================
    // RELACIONES PRINCIPALES
    // =============================================

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function ivaCompra()
    {
        return $this->belongsTo(TablaIva::class, 'iva_compra_id');
    }

    public function ivaVenta()
    {
        return $this->belongsTo(TablaIva::class, 'iva_venta_id');
    }

    // =============================================
    // RELACIONES CON DETALLES (PIVOTE)
    // =============================================

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

    // =============================================
    // RELACIONES MANY-TO-MANY
    // =============================================

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

    // =============================================
    // MOVIMIENTOS Y ALERTAS
    // =============================================

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
        return $this->attributes['stock_actual'] ?? 0;
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