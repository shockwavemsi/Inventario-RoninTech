<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlbaranCompraLinea extends Model
{
    protected $table = 'albaranes_compra_linea';

    protected $fillable = [
        'albaran_compra_id',
        'producto_id',
        'cantidad_pedida',
        'cantidad_recibida',
        'cantidad_faltante',
        'estado'
    ];

    public function albaranCompra()
    {
        return $this->belongsTo(AlbaranCompra::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}