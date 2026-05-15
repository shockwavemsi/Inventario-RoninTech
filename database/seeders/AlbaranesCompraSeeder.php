<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PedidoCompra;
use App\Models\AlbaranCompra;

class AlbaranesCompraSeeder extends Seeder
{
    public function run(): void
    {
        // ALBARÁN 1: del PEDIDO 1
        $pedido1 = PedidoCompra::find(1);
        AlbaranCompra::create([
            'numero_albaran' => 'ALB-COMP-001',
            'pedido_compra_id' => 1,
            'proveedor_id' => 1,
            'fecha_albaran' => '2026-05-10',
            'fecha_recepcion' => '2026-05-10',
            'estado' => 'recibido',
            'total' => $pedido1->total,  // ✅ COPIAR del Pedido
            'observaciones' => 'Recepción completa',
        ]);

        // ALBARÁN 2: del PEDIDO 2
        $pedido2 = PedidoCompra::find(2);
        AlbaranCompra::create([
            'numero_albaran' => 'ALB-COMP-002',
            'pedido_compra_id' => 2,
            'proveedor_id' => 2,
            'fecha_albaran' => '2026-05-12',
            'fecha_recepcion' => '2026-05-12',
            'estado' => 'parcial',
            'total' => $pedido2->total,  // ✅ COPIAR del Pedido
            'observaciones' => 'Entrega parcial',
        ]);

        // ALBARÁN 3: del PEDIDO 3
        $pedido3 = PedidoCompra::find(3);
        AlbaranCompra::create([
            'numero_albaran' => 'ALB-COMP-003',
            'pedido_compra_id' => 3,
            'proveedor_id' => 5,
            'fecha_albaran' => '2026-05-15',
            'fecha_recepcion' => '2026-05-15',
            'estado' => 'recibido',
            'total' => $pedido3->total,  // ✅ COPIAR del Pedido
            'observaciones' => 'Recepción correcta',
        ]);

        $this->command->info('✅ Albaranes de compra creados');
    }
}