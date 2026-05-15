<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DebitoCompra extends Model
{
    use SoftDeletes;

    protected $table = 'debitos_compra';

    protected $fillable = [
        'numero_debito',
        'albaran_compra_id',
        'proveedor_id',
        'fecha_debito',
        'fecha_vencimiento',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_debito' => 'date',
        'fecha_vencimiento' => 'date',
    ];

    // ✅ RELACIONES
    public function albaranCompra()
    {
        return $this->belongsTo(AlbaranCompra::class, 'albaran_compra_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function lineas()
    {
        return $this->hasMany(DebitoCompraLinea::class, 'debito_compra_id');
    }
}