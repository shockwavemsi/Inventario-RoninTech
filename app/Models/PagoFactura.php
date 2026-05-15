<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PagoFactura extends Model
{
    use SoftDeletes;

    protected $table = 'pagos_factura';

    protected $fillable = [
        'factura_compra_id',
        'metodo_pago_id',
        'monto',
        'fecha_pago',
        'referencia',
        'detalles',
        'estado',
        'usuario_id',
        'notas',
    ];

    protected $casts = [
        'detalles' => 'array',
        'fecha_pago' => 'date',
    ];

    public function factura()
    {
        return $this->belongsTo(FacturaCompra::class, 'factura_compra_id');
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