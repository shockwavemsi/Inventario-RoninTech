<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlbaranCompra extends Model
{
    use SoftDeletes;

    protected $table = 'albaranes_compra';

    protected $fillable = [
    'numero_albaran',
    'pedido_compra_id',
    'proveedor_id',
    'fecha_albaran',
    'fecha_recepcion',  
    'estado',
    'cantidad_total',
    'subtotal',
    'impuesto_total',
    'total',
    'observaciones',    
    ];

    // ✅ RELACIÓN CON PEDIDO
    public function pedidoCompra()
    {
        return $this->belongsTo(PedidoCompra::class, 'pedido_compra_id');
    }

    // ✅ RELACIÓN CON PROVEEDOR
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    // ✅ RELACIÓN CON USUARIO
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // ✅ RELACIÓN CON LÍNEAS
    public function lineas()
    {
        return $this->hasMany(AlbaranCompraLinea::class, 'albaran_compra_id');
    }

    // ✅ RELACIÓN CON DÉBITOS
    public function debitoCompra()
{
    return $this->hasOne(DebitoCompra::class, 'albaran_compra_id');
}

    // ✅ AGREGAR ESTO: Relación con Facturas
    public function facturas()
    {
        return $this->hasMany(FacturaCompra::class, 'albaran_compra_id');
    }
}