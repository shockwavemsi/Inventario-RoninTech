<?php

namespace Database\Seeders;

use App\Models\FacturaCompra;
use App\Models\FormasPagoProveedor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagoFacturaSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('💳 Creando pagos de facturas...');

        $facturas = FacturaCompra::all();

        if ($facturas->isEmpty()) {
            $this->command->warn('⚠️ No hay facturas.');
            return;
        }

        // FACTURA 1 - Pagada completamente
        if ($facturas->count() >= 1) {
            $f1 = $facturas->get(0);
            $formaPago1 = FormasPagoProveedor::where('proveedor_id', $f1->proveedor_id)
                ->whereHas('formaPago', fn($q) => $q->where('nombre', 'Transferencia Bancaria'))
                ->first();

            if ($formaPago1) {
                DB::table('pagos_factura')->insert([
                    'factura_compra_id' => $f1->id,
                    'forma_pago_proveedor_id' => $formaPago1->id,
                    'monto' => $f1->total,
                    'fecha_pago' => now()->subDays(5),
                    'referencia' => $formaPago1->referencia,
                    'estado' => 'pagado',
                    'usuario_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $this->command->line("✅ Pago agregado a FAC-COMP-{$f1->id}: {$f1->total}€");
            }
        }

        // FACTURA 2 - Pagada en partes
        if ($facturas->count() >= 2) {
            $f2 = $facturas->get(1);
            $formaPago2A = FormasPagoProveedor::where('proveedor_id', $f2->proveedor_id)
                ->whereHas('formaPago', fn($q) => $q->where('nombre', 'Transferencia Bancaria'))
                ->first();

            $formaPago2B = FormasPagoProveedor::where('proveedor_id', $f2->proveedor_id)
                ->whereHas('formaPago', fn($q) => $q->where('nombre', 'Cheque'))
                ->first();

            $monto1 = $f2->total * 0.6;
            $monto2 = $f2->total * 0.4;

            if ($formaPago2A) {
                DB::table('pagos_factura')->insert([
                    'factura_compra_id' => $f2->id,
                    'forma_pago_proveedor_id' => $formaPago2A->id,
                    'monto' => $monto1,
                    'fecha_pago' => now()->subDays(3),
                    'referencia' => $formaPago2A->referencia,
                    'estado' => 'pagado',
                    'usuario_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            if ($formaPago2B) {
                DB::table('pagos_factura')->insert([
                    'factura_compra_id' => $f2->id,
                    'forma_pago_proveedor_id' => $formaPago2B->id,
                    'monto' => $monto2,
                    'fecha_pago' => now()->addDays(5),
                    'referencia' => null,
                    'estado' => 'en_transito',
                    'usuario_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            $this->command->line("✅ Pagos agregados a FAC-COMP-{$f2->id}: {$monto1}€ + {$monto2}€");
        }

        // FACTURA 3 - Efectivo pendiente
        if ($facturas->count() >= 3) {
            $f3 = $facturas->get(2);
            $formaPago3 = FormasPagoProveedor::where('proveedor_id', $f3->proveedor_id)
                ->whereHas('formaPago', fn($q) => $q->where('nombre', 'Efectivo'))
                ->first();

            if ($formaPago3) {
                DB::table('pagos_factura')->insert([
                    'factura_compra_id' => $f3->id,
                    'forma_pago_proveedor_id' => $formaPago3->id,
                    'monto' => $f3->total,
                    'fecha_pago' => null,
                    'referencia' => null,
                    'estado' => 'pendiente',
                    'usuario_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $this->command->line("✅ Pago pendiente agregado a FAC-COMP-{$f3->id}: {$f3->total}€");
            }
        }

        $this->command->info('✅ Pagos creados correctamente');
    }
}