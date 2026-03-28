<?php

// database/seeders/VentaSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class VentaSeeder extends Seeder

{

    public function run(): void

    {

        $user = DB::table('users')->where('email', 'user@user.com')->first();

        $admin = DB::table('users')->where('email', 'admin@admin.com')->first();

        $productos = DB::table('productos')->get();

        $productoRyzen = $productos->where('nombre', 'Ryzen 5 5600G')->first();

        $productoG203 = $productos->where('nombre', 'Logitech G203')->first();

        $productoRtx = $productos->where('nombre', 'RTX 4060 8GB')->first();

        // =============================================

        // VENTA 1: User vende 2 Ryzen 5

        // =============================================

        $ventaId1 = DB::table('ventas')->insertGetId([

            'numero_factura' => 'FACT-2025-0001',

            'fecha_venta' => '2025-03-15 14:30:00',

            'cliente' => 'Juan PÃ©rez',

            'cliente_documento' => '12345678A',

            'subtotal' => 499.98,

            'impuesto' => 104.99,

            'total' => 604.97,

            'metodo_pago' => 'tarjeta',

            'estado' => 'completada',

            'usuario_id' => $user->id,

            'observaciones' => 'Cliente habitual',

            'created_at' => '2025-03-15 14:30:00',

            'updated_at' => '2025-03-15 14:30:00'

        ]);

        DB::table('ventas_detalle')->insert([

            [

                'venta_id' => $ventaId1,

                'producto_id' => $productoRyzen->id,

                'cantidad' => 2,

                'precio_unitario' => 249.99,

                'subtotal' => 499.98,

                'created_at' => now(),

                'updated_at' => now()

            ]

        ]);

        // Movimiento de stock - TABLA CORRECTA: movimientos_stock

        DB::table('movimientos_stock')->insert([

            [

                'producto_id' => $productoRyzen->id,

                'tipo' => 'salida_venta',

                'cantidad' => 2,

                'stock_anterior' => 5,

                'stock_nuevo' => 3,

                'referencia_tipo' => 'venta',

                'referencia_id' => $ventaId1,

                'usuario_id' => $user->id,

                'created_at' => '2025-03-15 14:30:00',

                'updated_at' => '2025-03-15 14:30:00'

            ]

        ]);

        // =============================================

        // VENTA 2: Admin vende 3 Logitech G203

        // =============================================

        $ventaId2 = DB::table('ventas')->insertGetId([

            'numero_factura' => 'FACT-2025-0002',

            'fecha_venta' => '2025-03-16 11:15:00',

            'cliente' => 'MarÃ­a GarcÃ­a',

            'cliente_documento' => '87654321B',

            'subtotal' => 89.97,

            'impuesto' => 18.89,

            'total' => 108.86,

            'metodo_pago' => 'efectivo',

            'estado' => 'completada',

            'usuario_id' => $admin->id,

            'created_at' => '2025-03-16 11:15:00',

            'updated_at' => '2025-03-16 11:15:00'

        ]);

        DB::table('ventas_detalle')->insert([

            [

                'venta_id' => $ventaId2,

                'producto_id' => $productoG203->id,

                'cantidad' => 3,

                'precio_unitario' => 29.99,

                'subtotal' => 89.97,

                'created_at' => now(),

                'updated_at' => now()

            ]

        ]);

        DB::table('movimientos_stock')->insert([

            [

                'producto_id' => $productoG203->id,

                'tipo' => 'salida_venta',

                'cantidad' => 3,

                'stock_anterior' => 20,

                'stock_nuevo' => 17,

                'referencia_tipo' => 'venta',

                'referencia_id' => $ventaId2,

                'usuario_id' => $admin->id,

                'created_at' => '2025-03-16 11:15:00',

                'updated_at' => '2025-03-16 11:15:00'

            ]

        ]);

        // =============================================

        // VENTA 3: User vende 1 RTX 4060

        // =============================================

        $ventaId3 = DB::table('ventas')->insertGetId([

            'numero_factura' => 'FACT-2025-0003',

            'fecha_venta' => '2025-03-17 09:45:00',

            'cliente' => 'Carlos LÃ³pez',

            'cliente_documento' => '11223344C',

            'subtotal' => 349.99,

            'impuesto' => 73.50,

            'total' => 423.49,

            'metodo_pago' => 'tarjeta',

            'estado' => 'completada',

            'usuario_id' => $user->id,

            'created_at' => '2025-03-17 09:45:00',

            'updated_at' => '2025-03-17 09:45:00'

        ]);

        DB::table('ventas_detalle')->insert([

            [

                'venta_id' => $ventaId3,

                'producto_id' => $productoRtx->id,

                'cantidad' => 1,

                'precio_unitario' => 349.99,

                'subtotal' => 349.99,

                'created_at' => now(),

                'updated_at' => now()

            ]

        ]);

        DB::table('movimientos_stock')->insert([

            [

                'producto_id' => $productoRtx->id,

                'tipo' => 'salida_venta',

                'cantidad' => 1,

                'stock_anterior' => 2,

                'stock_nuevo' => 1,

                'referencia_tipo' => 'venta',

                'referencia_id' => $ventaId3,

                'usuario_id' => $user->id,

                'created_at' => '2025-03-17 09:45:00',

                'updated_at' => '2025-03-17 09:45:00'

            ]

        ]);

        // Alerta de stock bajo para RTX 4060

        DB::table('alertas')->insert([

            'producto_id' => $productoRtx->id,

            'tipo' => 'stock_bajo',

            'mensaje' => 'Stock bajo de RTX 4060: 1 unidad (mÃ­nimo 2)',

            'vista' => false,

            'created_at' => now(),

            'updated_at' => now()

        ]);

    }

}
