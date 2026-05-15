<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PedidosCompraLineaSeeder extends Seeder
{
    public function run(): void
    {
        $lineas = [
            // Pedido PC-001
            [
                'pedido_compra_id' => 1,
                'producto_id' => 4,  // RTX 4060
                'cantidad' => 1,
                'precio_unitario' => 338.80,  // precio_compra_final
                'total' => 338.80
            ],
            [
                'pedido_compra_id' => 1,
                'producto_id' => 8,  // Samsung 980
                'cantidad' => 1,
                'precio_unitario' => 79.95,
                'total' => 79.95
            ],
            [
                'pedido_compra_id' => 1,
                'producto_id' => 10,  // Corsair RAM
                'cantidad' => 1,
                'precio_unitario' => 67.65,
                'total' => 67.65
            ],
            // Pedido PC-002
            [
                'pedido_compra_id' => 2,
                'producto_id' => 1,  // Ryzen 5600G
                'cantidad' => 1,
                'precio_unitario' => 218.41,
                'total' => 218.41
            ],
            [
                'pedido_compra_id' => 2,
                'producto_id' => 2,  // Ryzen 7700X
                'cantidad' => 1,
                'precio_unitario' => 302.50,
                'total' => 302.50
            ],
            // Pedido PC-003
            [
                'pedido_compra_id' => 3,
                'producto_id' => 3,  // Core i5-12400F
                'cantidad' => 1,
                'precio_unitario' => 181.50,
                'total' => 181.50
            ],
        ];

        DB::table('pedidos_compra_linea')->insert($lineas);
        $this->command->info('✅ Líneas de pedidos de compra creadas');
    }
}