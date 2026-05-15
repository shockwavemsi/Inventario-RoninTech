<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PedidosVentaSeeder extends Seeder
{
    public function run(): void
    {
        $pedidos = [
            [
                'numero_pedido' => 'PV-001',
                'cliente_nombre' => 'DISTEC S.L.',
                'cliente_documento' => 'B12345678',
                'cliente_email' => 'ventas@distec.com',
                'fecha_pedido' => '2026-05-02',
                'fecha_entrega_esperada' => '2026-05-08',
                'estado' => 'entregado',
                'metodo_pago' => 'transferencia',
                'subtotal' => 750.00,
                'impuesto' => 172.50,
                'total' => 922.50,
                'observaciones' => 'Venta a cliente VIP',
                'usuario_id' => 1
            ],
            [
                'numero_pedido' => 'PV-002',
                'cliente_nombre' => 'PC Componentes',
                'cliente_documento' => 'B87654321',
                'cliente_email' => 'info@pccomponentes.com',
                'fecha_pedido' => '2026-05-04',
                'fecha_entrega_esperada' => '2026-05-11',
                'estado' => 'entregado_parcial',
                'metodo_pago' => 'tarjeta',
                'subtotal' => 400.00,
                'impuesto' => 84.00,
                'total' => 484.00,
                'observaciones' => 'Venta normal',
                'usuario_id' => 1
            ],
        ];

        DB::table('pedidos_venta')->insert($pedidos);
        $this->command->info('✅ Pedidos de venta creados');
    }
}