<?php

// database/seeders/CompraSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class CompraSeeder extends Seeder

{

    public function run(): void

    {

        $proveedorDistec = DB::table('proveedores')->where('nombre', 'DISTEC S.L.')->first();

        $proveedorLogitech = DB::table('proveedores')->where('nombre', 'Logitech Iberia')->first();

        $proveedorAmd = DB::table('proveedores')->where('nombre', 'AMD Direct')->first();

        $proveedorIntel = DB::table('proveedores')->where('nombre', 'Intel Spain')->first();

        $admin = DB::table('users')->where('email', 'admin@admin.com')->first();

        $productos = DB::table('productos')->get();

        // Obtener todos los productos por nombre
        $productoRtx = $productos->where('nombre', 'RTX 4060 8GB')->first();
        $productoSamsung = $productos->where('nombre', 'Samsung 980 1TB NVMe')->first();
        $productoCorsair = $productos->where('nombre', 'Corsair Vengeance 16GB 3200MHz')->first();
        $productoRyzen5 = $productos->where('nombre', 'Ryzen 5 5600G')->first();
        $productoRyzen7 = $productos->where('nombre', 'Ryzen 7 5700X')->first();
        $productoG203 = $productos->where('nombre', 'Logitech G203')->first();
        $productoG502 = $productos->where('nombre', 'Logitech G502 Hero')->first();
        $productoIntel = $productos->where('nombre', 'Core i5-12400F')->first();
        $productoRx = $productos->where('nombre', 'RX 6600 8GB')->first();

        // =============================================

        // COMPRA 1: DISTEC - Varios productos

        // =============================================

        $compraId1 = DB::table('compras')->insertGetId([

            'proveedor_id' => $proveedorDistec->id,

            'numero_factura' => 'FAC-2025-001',

            'fecha_pedido' => '2025-03-01',

            'fecha_entrega_esperada' => '2025-03-08',

            'fecha_entrega_real' => '2025-03-07',

            'subtotal' => 1105.00,

            'impuesto' => 232.05,

            'total' => 1337.05,

            'estado' => 'recibido',

            'usuario_id' => $admin->id,

            'observaciones' => 'Pedido inicial de productos NVIDIA y Samsung',

            'created_at' => '2025-03-01 10:00:00',

            'updated_at' => '2025-03-07 15:30:00'

        ]);

        // Detalles de compra 1 (tabla pivote sin modelo)

        DB::table('compras_detalle')->insert([

            [

                'compra_id' => $compraId1,

                'producto_id' => $productoRtx->id,

                'cantidad' => 2,

                'precio_unitario' => 280.00,

                'subtotal' => 560.00,

                'created_at' => now(),

                'updated_at' => now()

            ],

            [

                'compra_id' => $compraId1,

                'producto_id' => $productoSamsung->id,

                'cantidad' => 5,

                'precio_unitario' => 65.00,

                'subtotal' => 325.00,

                'created_at' => now(),

                'updated_at' => now()

            ],

            [

                'compra_id' => $compraId1,

                'producto_id' => $productoCorsair->id,

                'cantidad' => 4,

                'precio_unitario' => 55.00,

                'subtotal' => 220.00,

                'created_at' => now(),

                'updated_at' => now()

            ]

        ]);

        // Movimientos de stock para compra 1

        $this->registrarMovimientoStock($productoRtx->id, 'entrada_compra', 2, 'compra', $compraId1, $admin->id, '2025-03-07 15:30:00');

        $this->registrarMovimientoStock($productoSamsung->id, 'entrada_compra', 5, 'compra', $compraId1, $admin->id, '2025-03-07 15:30:00');

        $this->registrarMovimientoStock($productoCorsair->id, 'entrada_compra', 4, 'compra', $compraId1, $admin->id, '2025-03-07 15:30:00');

        // =============================================

        // COMPRA 2: AMD Direct - Procesadores

        // =============================================

        $compraId2 = DB::table('compras')->insertGetId([

            'proveedor_id' => $proveedorAmd->id,

            'numero_factura' => 'FAC-2025-002',

            'fecha_pedido' => '2025-03-05',

            'fecha_entrega_esperada' => '2025-03-12',

            'fecha_entrega_real' => '2025-03-11',

            'subtotal' => 2152.50,

            'impuesto' => 452.03,

            'total' => 2604.53,

            'estado' => 'recibido',

            'usuario_id' => $admin->id,

            'observaciones' => 'Pedido de procesadores AMD',

            'created_at' => '2025-03-05 09:30:00',

            'updated_at' => '2025-03-11 14:20:00'

        ]);

        DB::table('compras_detalle')->insert([

            [

                'compra_id' => $compraId2,

                'producto_id' => $productoRyzen5->id,

                'cantidad' => 10,

                'precio_unitario' => 180.50,

                'subtotal' => 1805.00,

                'created_at' => now(),

                'updated_at' => now()

            ],

            [

                'compra_id' => $compraId2,

                'producto_id' => $productoRyzen7->id,

                'cantidad' => 5,

                'precio_unitario' => 250.00,

                'subtotal' => 1250.00,

                'created_at' => now(),

                'updated_at' => now()

            ]

        ]);

        $this->registrarMovimientoStock($productoRyzen5->id, 'entrada_compra', 10, 'compra', $compraId2, $admin->id, '2025-03-11 14:20:00');

        $this->registrarMovimientoStock($productoRyzen7->id, 'entrada_compra', 5, 'compra', $compraId2, $admin->id, '2025-03-11 14:20:00');

        // =============================================

        // COMPRA 3: Logitech - PerifÃ©ricos

        // =============================================

        $compraId3 = DB::table('compras')->insertGetId([

            'proveedor_id' => $proveedorLogitech->id,

            'numero_factura' => 'FAC-2025-003',

            'fecha_pedido' => '2025-03-10',

            'fecha_entrega_esperada' => '2025-03-15',

            'fecha_entrega_real' => '2025-03-14',

            'subtotal' => 710.00,

            'impuesto' => 149.10,

            'total' => 859.10,

            'estado' => 'recibido',

            'usuario_id' => $admin->id,

            'observaciones' => 'Stock de perifÃ©ricos Logitech',

            'created_at' => '2025-03-10 11:00:00',

            'updated_at' => '2025-03-14 10:15:00'

        ]);

        DB::table('compras_detalle')->insert([

            [

                'compra_id' => $compraId3,

                'producto_id' => $productoG203->id,

                'cantidad' => 20,

                'precio_unitario' => 18.00,

                'subtotal' => 360.00,

                'created_at' => now(),

                'updated_at' => now()

            ],

            [

                'compra_id' => $compraId3,

                'producto_id' => $productoG502->id,

                'cantidad' => 10,

                'precio_unitario' => 35.00,

                'subtotal' => 350.00,

                'created_at' => now(),

                'updated_at' => now()

            ]

        ]);

        $this->registrarMovimientoStock($productoG203->id, 'entrada_compra', 20, 'compra', $compraId3, $admin->id, '2025-03-14 10:15:00');

        $this->registrarMovimientoStock($productoG502->id, 'entrada_compra', 10, 'compra', $compraId3, $admin->id, '2025-03-14 10:15:00');

        // =============================================

        // COMPRA 4: Intel Spain - Procesadores Intel

        // =============================================

        $compraId4 = DB::table('compras')->insertGetId([

            'proveedor_id' => $proveedorIntel->id,

            'numero_factura' => 'FAC-2025-004',

            'fecha_pedido' => '2025-03-12',

            'fecha_entrega_esperada' => '2025-03-19',

            'fecha_entrega_real' => '2025-03-18',

            'subtotal' => 1500.00,

            'impuesto' => 315.00,

            'total' => 1815.00,

            'estado' => 'recibido',

            'usuario_id' => $admin->id,

            'created_at' => '2025-03-12 08:00:00',

            'updated_at' => '2025-03-18 16:45:00'

        ]);

        DB::table('compras_detalle')->insert([

            [

                'compra_id' => $compraId4,

                'producto_id' => $productoIntel->id,

                'cantidad' => 10,

                'precio_unitario' => 150.00,

                'subtotal' => 1500.00,

                'created_at' => now(),

                'updated_at' => now()

            ]

        ]);

        $this->registrarMovimientoStock($productoIntel->id, 'entrada_compra', 10, 'compra', $compraId4, $admin->id, '2025-03-18 16:45:00');

    }

    /**

     * MÃ©todo auxiliar para registrar movimientos de stock

     */

    private function registrarMovimientoStock($productoId, $tipo, $cantidad, $referenciaTipo, $referenciaId, $usuarioId, $fecha)

    {

        // Calcular stock actual antes del movimiento

        $stockActual = DB::table('movimientos_stock')

            ->where('producto_id', $productoId)

            ->sum(DB::raw("CASE WHEN tipo IN ('entrada_compra', 'devolucion_venta', 'inventario_inicial') THEN cantidad ELSE -cantidad END"));

        $stockAnterior = $stockActual;

        

        if (in_array($tipo, ['entrada_compra', 'devolucion_venta', 'inventario_inicial'])) {

            $stockNuevo = $stockAnterior + $cantidad;

        } else {

            $stockNuevo = $stockAnterior - $cantidad;

        }

        DB::table('movimientos_stock')->insert([

            'producto_id' => $productoId,

            'tipo' => $tipo,

            'cantidad' => $cantidad,

            'stock_anterior' => $stockAnterior,

            'stock_nuevo' => $stockNuevo,

            'referencia_tipo' => $referenciaTipo,

            'referencia_id' => $referenciaId,

            'usuario_id' => $usuarioId,

            'created_at' => $fecha,

            'updated_at' => $fecha

        ]);

    }

}
