<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacturasVentaLineaSeeder extends Seeder
{
    public function run(): void
    {
        $lineas = [
            // Factura FAC-VENTA-001
            [
                'factura_venta_id' => 1,
                'albaran_venta_linea_id' => 1,
                'producto_id' => 1,
                'cantidad' => 2,
                'precio_unitario' => 249.99,
                'impuesto_porcentaje' => 21.00,
                'impuesto_cantidad' => 104.97,
                'descuento' => 49.98,
                'total' => 654.95
            ],
            [
                'factura_venta_id' => 1,
                'albaran_venta_linea_id' => 2,
                'producto_id' => 7,
                'cantidad' => 1,
                'precio_unitario' => 49.99,
                'impuesto_porcentaje' => 0.00,
                'impuesto_cantidad' => 0.00,
                'descuento' => 0.00,
                'total' => 49.99
            ],
            [
                'factura_venta_id' => 1,
                'albaran_venta_linea_id' => 3,
                'producto_id' => 9,
                'cantidad' => 1,
                'precio_unitario' => 35.00,
                'impuesto_porcentaje' => 23.00,
                'impuesto_cantidad' => 8.05,
                'descuento' => 0.00,
                'total' => 43.05
            ],
            // Factura FAC-VENTA-002
            [
                'factura_venta_id' => 2,
                'albaran_venta_linea_id' => 4,
                'producto_id' => 4,
                'cantidad' => 1,
                'precio_unitario' => 349.99,
                'impuesto_porcentaje' => 21.00,
                'impuesto_cantidad' => 73.50,
                'descuento' => 17.50,
                'total' => 423.49
            ],
        ];

        DB::table('facturas_venta_linea')->insert($lineas);
        $this->command->info('✅ Líneas de facturas de venta creadas');
    }
}