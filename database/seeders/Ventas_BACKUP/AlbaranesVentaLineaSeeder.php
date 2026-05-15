<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlbaranesVentaLineaSeeder extends Seeder
{
    public function run(): void
    {
        $lineas = [
            // ALB-VENTA-001
            [
                'albaran_venta_id' => 1,
                'pedido_venta_linea_id' => 1,
                'producto_id' => 1,
                'cantidad_entregada' => 2,
                'cantidad_rechazada' => 0,
                'precio_unitario' => 249.99,
                'impuesto_porcentaje' => 21.00,
                'impuesto_cantidad' => 104.97,
                'total' => 654.95
            ],
            [
                'albaran_venta_id' => 1,
                'pedido_venta_linea_id' => 2,
                'producto_id' => 7,
                'cantidad_entregada' => 1,
                'cantidad_rechazada' => 0,
                'precio_unitario' => 49.99,
                'impuesto_porcentaje' => 0.00,
                'impuesto_cantidad' => 0.00,
                'total' => 49.99
            ],
            [
                'albaran_venta_id' => 1,
                'pedido_venta_linea_id' => 3,
                'producto_id' => 9,
                'cantidad_entregada' => 1,
                'cantidad_rechazada' => 0,
                'precio_unitario' => 35.00,
                'impuesto_porcentaje' => 23.00,
                'impuesto_cantidad' => 8.05,
                'total' => 43.05
            ],
            // ALB-VENTA-002
            [
                'albaran_venta_id' => 2,
                'pedido_venta_linea_id' => 4,
                'producto_id' => 4,
                'cantidad_entregada' => 1,
                'cantidad_rechazada' => 0,
                'precio_unitario' => 349.99,
                'impuesto_porcentaje' => 21.00,
                'impuesto_cantidad' => 73.50,
                'total' => 423.49
            ],
        ];

        DB::table('albaranes_venta_linea')->insert($lineas);
        $this->command->info('✅ Líneas de albaranes de venta creadas');
    }
}