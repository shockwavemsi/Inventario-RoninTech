<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlbaranesVentaSeeder extends Seeder
{
    public function run(): void
    {
        $albaranes = [
            [
                'numero_albaran' => 'ALB-VENTA-001',
                'pedido_venta_id' => 1,
                'usuario_id' => 1,
                'fecha_albaran' => '2026-05-08',
                'estado' => 'entregado',
                'subtotal' => 750.00,
                'impuesto' => 172.50,
                'total' => 922.50,
                'observaciones' => 'Entrega completada'
            ],
            [
                'numero_albaran' => 'ALB-VENTA-002',
                'pedido_venta_id' => 2,
                'usuario_id' => 1,
                'fecha_albaran' => '2026-05-11',
                'estado' => 'entregado',
                'subtotal' => 400.00,
                'impuesto' => 84.00,
                'total' => 484.00,
                'observaciones' => 'Entrega completada'
            ],
        ];

        DB::table('albaranes_venta')->insert($albaranes);
        $this->command->info('✅ Albaranes de venta creados');
    }
}