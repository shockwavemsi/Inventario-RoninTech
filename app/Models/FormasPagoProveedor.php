<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormasPagoProveedor extends Model
{
    protected $table = 'formas_pago_proveedor';
    protected $fillable = ['proveedor_id', 'forma_pago_id', 'banco_id', 'referencia', 'nombre_banco'];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function formaPago()
    {
        return $this->belongsTo(FormaPago::class, 'forma_pago_id');
    }

    public function banco()
    {
        return $this->belongsTo(Banco::class, 'banco_id');
    }
}