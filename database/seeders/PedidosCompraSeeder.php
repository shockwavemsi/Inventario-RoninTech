<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PedidosCompraSeeder extends Seeder
{
    public function run(): void
    {
        $proveedor1 = DB::table('proveedores')->where('nombre', 'DISTEC S.L.')->first();
        $proveedor2 = DB::table('proveedores')->where('nombre', 'PC Componentes')->first();
        $proveedor3 = DB::table('proveedores')->where('nombre', 'Intel Spain')->first();

        $pedidos = [
    [
        'numero_pedido' => 'PC-001',
        'proveedor_id' => $proveedor1->id,
        'usuario_id' => 1,
        'fecha_pedido' => '2026-05-01',
        'fecha_entrega_esperada' => '2026-05-10',
        'estado' => 'completo',
        'subtotal' => 486.40,
        'descuento_porcentaje' => 0.00,
        'descuento_cantidad' => 0.00,
        'total' => 486.40,
        'observaciones' => 'Pedido de componentes para stock'
    ],
    [
        'numero_pedido' => 'PC-002',
        'proveedor_id' => $proveedor2->id,
        'usuario_id' => 1,
        'fecha_pedido' => '2026-05-03',
        'fecha_entrega_esperada' => '2026-05-12',
        'estado' => 'parcial',
        'subtotal' => 520.91,
        'descuento_porcentaje' => 5.00,
        'descuento_cantidad' => 26.05,
        'total' => 494.86,
        'observaciones' => 'Procesadores AMD con descuento'
    ],
    [
        'numero_pedido' => 'PC-003',
        'proveedor_id' => $proveedor3->id,
        'usuario_id' => 1,
        'fecha_pedido' => '2026-05-05',
        'fecha_entrega_esperada' => '2026-05-15',
        'estado' => 'abierto',
        'subtotal' => 181.50,
        'descuento_porcentaje' => 0.00,
        'descuento_cantidad' => 0.00,
        'total' => 181.50,
        'observaciones' => 'Procesadores Intel Core'
    ],
];

        DB::table('pedidos_compra')->insert($pedidos);
        $this->command->info('✅ Pedidos de compra creados');
    }
}