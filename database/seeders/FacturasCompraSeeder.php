<?php

namespace Database\Seeders;

use App\Models\AlbaranCompra;
use App\Models\FacturaCompra;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FacturasCompraSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📄 Creando facturas de compra...');

        $albaranes = AlbaranCompra::all();

        if ($albaranes->isEmpty()) {
            $this->command->warn('⚠️ No hay albaranes.');
            return;
        }

        foreach ($albaranes as $alb) {
            $fecha_factura = is_string($alb->fecha_albaran) 
                ? Carbon::parse($alb->fecha_albaran) 
                : $alb->fecha_albaran;

            FacturaCompra::create([
                'numero_factura' => 'FAC-COMP-' . str_pad($alb->id, 3, '0', STR_PAD_LEFT),
                'albaran_compra_id' => $alb->id,
                'proveedor_id' => $alb->proveedor_id,
                'fecha_factura' => $fecha_factura,
                'fecha_vencimiento' => $fecha_factura->copy()->addDays(30),
                'estado' => 'abierta',
                'total' => $alb->total,  // ✅ COPIAR del Albarán
                'observaciones' => 'Factura por recepción',
            ]);

            $this->command->line("✅ FAC-COMP-{$alb->id} creada: {$alb->total}€");
        }

        $this->command->info('✅ Facturas creadas correctamente');
    }
}