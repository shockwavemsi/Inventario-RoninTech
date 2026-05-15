<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagosVentaSeeder extends Seeder
{
    public function run(): void
    {
        $metodoTransf = DB::table('metodos_pago')->where('nombre', 'Transferencia Bancaria')->first();
        $metodoTarjeta = DB::table('metodos_pago')->where('nombre', 'Tarjeta de Crédito')->first();

        $pagos = [
            [
                'factura_venta_id' => 1,
                'metodo_pago_id' => $metodoTransf->id,
                'monto' => 922.50,
                'fecha_pago' => '2026-05-10',
                'referencia' => 'REF-CLIENTE-001',
                'detalles' => json_encode(['banco' => 'Banco del Cliente', 'cuenta' => 'ES92...']),
                'estado' => 'pagado',
                'usuario_id' => 1,
                'notas' => 'Pago recibido correctamente'
            ],
            [
                'factura_venta_id' => 2,
                'metodo_pago_id' => $metodoTarjeta->id,
                'monto' => 484.00,
                'fecha_pago' => '2026-05-25',
                'referencia' => 'TRX-TARJETA-001',
                'detalles' => json_encode(['ultimos_digitos' => '4532', 'banco' => 'Visa']),
                'estado' => 'pendiente',
                'usuario_id' => 1,
                'notas' => 'Pago por tarjeta en proceso'
            ],
        ];

        DB::table('pagos_venta')->insert($pagos);
        $this->command->info('✅ Pagos de venta creados');
    }
}