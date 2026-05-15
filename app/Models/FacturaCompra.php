<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacturaCompra extends Model
{
    use SoftDeletes;

    protected $table = 'facturas_compra';

    protected $fillable = [
        'numero_factura',
        'albaran_compra_id',
        'proveedor_id',
        'usuario_id',
        'fecha_factura',
        'fecha_vencimiento',
        'estado',
        'total',
        'observaciones',
    ];

    protected $casts = [
        'fecha_factura' => 'date',
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

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function lineas()
    {
        return $this->hasMany(FacturaCompraLinea::class, 'factura_compra_id');
    }

    // ✅ Los débitos están en el Albarán, no en la Factura
    public function debitoCompra()
    {
        return $this->albaranCompra->debitoCompra();
    }

    public function pagos()
    {
        return $this->hasMany(PagoFactura::class, 'factura_compra_id');
    }

    // ✅ MÉTODOS DINÁMICOS PARA ESTADO
    public function getTotalPagado()
{
    return $this->pagos()
        ->where('estado', 'pagado')  // ← SOLO PAGADO
        ->sum('monto');
}

public function getTotalPendiente()
{
    return $this->total - $this->getTotalPagado();
}

public function getTotalEnTransito()
{
    return $this->pagos()
        ->where('estado', 'en_transito')
        ->sum('monto');
}

public function calcularEstado()
{
    $totalPagado = $this->getTotalPagado();

    if ($totalPagado >= $this->total) {
        return 'pagada';
    }

    if ($this->fecha_vencimiento && $this->fecha_vencimiento < today() && $totalPagado < $this->total) {
        return 'vencida';
    }

    return 'abierta';
}

public function getResumenPagos()
{
    return [
        'total_comprometido' => (float) $this->total,
        'pagado' => (float) $this->getTotalPagado(),
        'en_transito' => (float) $this->getTotalEnTransito(),
        'pendiente' => (float) $this->getTotalPendiente(),
        'estado' => $this->calcularEstado(),
    ];
}

public function actualizarEstadoAutomatico()
{
    $nuevoEstado = $this->calcularEstado();
    if ($this->estado !== $nuevoEstado) {
        $this->update(['estado' => $nuevoEstado]);
    }
}
}