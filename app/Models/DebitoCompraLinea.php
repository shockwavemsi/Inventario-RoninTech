<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DebitoCompraLinea extends Model
{
    protected $table = 'debitos_compra_linea';

    protected $fillable = [
        'debito_compra_id',
        'producto_id',
        'cantidad',
        'estado',
    ];

    // ✅ RELACIONES
    public function debitoCompra()
    {
        return $this->belongsTo(DebitoCompra::class, 'debito_compra_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}