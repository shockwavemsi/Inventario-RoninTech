<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PedidosVentaLineaSeeder extends Seeder
{
    public function run(): void
    {
        $productos = DB::table('productos')->get()->keyBy('nombre');

        $lineas = [
            // PV-001
            [
                'pedido_venta_id' => 1,
                'producto_id' => $productos['Ryzen 5 5600G']->id,
                'cantidad' => 2,
                'precio_unitario' => 249.99,
                'impuesto_porcentaje' => 21.00,
                'impuesto_cantidad' => 104.97,
                'descuento' => 49.98,
                'total' => 654.95
            ],
            [
                'pedido_venta_id' => 1,
                'producto_id' => $productos['Logitech G502 Hero']->id,
                'cantidad' => 1,
                'precio_unitario' => 49.99,
                'impuesto_porcentaje' => 0.00,
                'impuesto_cantidad' => 0.00,
                'descuento' => 0.00,
                'total' => 49.99
            ],
            [
                'pedido_venta_id' => 1,
                'producto_id' => $productos['Kingston A2000 500GB']->id,
                'cantidad' => 1,
                'precio_unitario' => 35.00,
                'impuesto_porcentaje' => 23.00,
                'impuesto_cantidad' => 8.05,
                'descuento' => 0.00,
                'total' => 43.05
            ],
            // PV-002
            [
                'pedido_venta_id' => 2,
                'producto_id' => $productos['RTX 4060 8GB']->id,
                'cantidad' => 1,
                'precio_unitario' => 349.99,
                'impuesto_porcentaje' => 21.00,
                'impuesto_cantidad' => 73.50,
                'descuento' => 17.50,
                'total' => 423.49
            ],
        ];

        DB::table('pedidos_venta_linea')->insert($lineas);
        $this->command->info('✅ Líneas de pedidos de venta creadas');
    }
}