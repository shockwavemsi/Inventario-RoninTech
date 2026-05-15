<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoVenta extends Model
{
    protected $table = 'pagos_venta';
    protected $fillable = [
        'factura_venta_id',
        'metodo_pago_id',
        'monto',
        'fecha_pago',
        'referencia',
        'detalles',
        'estado',
        'usuario_id',
        'notas'
    ];

    protected $casts = [
        'detalles' => 'json',
        'fecha_pago' => 'date',
    ];

    public function facturaVenta()
    {
        return $this->belongsTo(FacturaVenta::class, 'factura_venta_id');
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}