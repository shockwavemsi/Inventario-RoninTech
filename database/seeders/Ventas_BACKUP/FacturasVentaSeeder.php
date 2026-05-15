<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacturasVentaSeeder extends Seeder
{
    public function run(): void
    {
        $facturas = [
            [
                'numero_factura' => 'FAC-VENTA-001',
                'albaran_venta_id' => 1,
                'usuario_id' => 1,
                'fecha_factura' => '2026-05-09',
                'fecha_vencimiento' => '2026-06-08',
                'estado' => 'cobrada',
                'subtotal' => 750.00,
                'impuesto' => 172.50,
                'total' => 922.50,
                'observaciones' => 'Factura pagada por cliente'
            ],
            [
                'numero_factura' => 'FAC-VENTA-002',
                'albaran_venta_id' => 2,
                'usuario_id' => 1,
                'fecha_factura' => '2026-05-12',
                'fecha_vencimiento' => '2026-06-11',
                'estado' => 'abierta',
                'subtotal' => 400.00,
                'impuesto' => 84.00,
                'total' => 484.00,
                'observaciones' => 'Pendiente de pago'
            ],
        ];

        DB::table('facturas_venta')->insert($facturas);
        $this->command->info('✅ Facturas de venta creadas');
    }
}