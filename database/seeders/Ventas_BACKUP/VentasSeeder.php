<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VentasSeeder extends Seeder
{
    public function run(): void
    {
        $admin = DB::table('users')->where('email', 'admin@admin.com')->first();
        $productos = DB::table('productos')->get();
        $productoRtx = $productos->where('nombre', 'RTX 4060 8GB')->first();
        $productoG203 = $productos->where('nombre', 'Logitech G203')->first();

        // VENTA 1: COMPLETA
        $pedidoId1 = DB::table('pedidos_venta')->insertGetId([
            'numero_pedido' => 'PV-001',
            'cliente_nombre' => 'Juan García',
            'cliente_documento' => '12345678A',
            'cliente_email' => 'juan@ejemplo.com',
            'fecha_pedido' => '2026-05-01',
            'fecha_entrega_esperada' => '2026-05-02',
            'estado' => 'entregado',
            'metodo_pago' => 'tarjeta',
            'subtotal' => 1400.00,
            'impuesto' => 294.00,
            'total' => 1694.00,
            'observaciones' => 'Venta completada',
            'usuario_id' => $admin->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $pedidoLineaId1 = DB::table('pedidos_venta_linea')->insertGetId([
            'pedido_venta_id' => $pedidoId1,
            'producto_id' => $productoRtx->id,
            'cantidad' => 5,
            'precio_unitario' => 280.00,
            'impuesto_porcentaje' => 21.00,
            'impuesto_cantidad' => 294.00,
            'descuento' => 0,
            'total' => 1694.00,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $albaranId1 = DB::table('albaranes_venta')->insertGetId([
            'pedido_venta_id' => $pedidoId1,
            'numero_albaran' => 'ALB-V-001',
            'fecha_albaran' => '2026-05-02',
            'estado' => 'entregado',
            'subtotal' => 1400.00,
            'impuesto' => 294.00,
            'total' => 1694.00,
            'observaciones' => 'Entregado completamente',
            'usuario_id' => $admin->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $albaranLineaId1 = DB::table('albaranes_venta_linea')->insertGetId([
            'albaran_venta_id' => $albaranId1,
            'pedido_venta_linea_id' => $pedidoLineaId1,
            'producto_id' => $productoRtx->id,
            'cantidad_entregada' => 5,
            'cantidad_rechazada' => 0,
            'precio_unitario' => 280.00,
            'impuesto_porcentaje' => 21.00,
            'impuesto_cantidad' => 294.00,
            'total' => 1694.00,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('productos')->where('id', $productoRtx->id)->decrement('stock_actual', 5);

        $facturaId1 = DB::table('facturas_venta')->insertGetId([
            'albaran_venta_id' => $albaranId1,
            'numero_factura' => 'FAC-V-001',
            'fecha_factura' => '2026-05-03',
            'fecha_vencimiento' => '2026-05-30',
            'estado' => 'cobrada',
            'subtotal' => 1400.00,
            'impuesto' => 294.00,
            'total' => 1694.00,
            'observaciones' => 'Factura cobrada',
            'usuario_id' => $admin->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('facturas_venta_linea')->insert([
            [
                'factura_venta_id' => $facturaId1,
                'albaran_venta_linea_id' => $albaranLineaId1,
                'producto_id' => $productoRtx->id,
                'cantidad' => 5,
                'precio_unitario' => 280.00,
                'impuesto_porcentaje' => 21.00,
                'impuesto_cantidad' => 294.00,
                'descuento' => 0,
                'total' => 1694.00,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // VENTA 2: PARCIAL
        $pedidoId2 = DB::table('pedidos_venta')->insertGetId([
            'numero_pedido' => 'PV-002',
            'cliente_nombre' => 'María López',
            'cliente_documento' => '87654321B',
            'cliente_email' => 'maria@ejemplo.com',
            'fecha_pedido' => '2026-05-04',
            'fecha_entrega_esperada' => '2026-05-05',
            'estado' => 'entregado_parcial',
            'metodo_pago' => 'transferencia',
            'subtotal' => 360.00,
            'impuesto' => 75.60,
            'total' => 435.60,
            'observaciones' => 'Venta parcial',
            'usuario_id' => $admin->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $pedidoLineaId2 = DB::table('pedidos_venta_linea')->insertGetId([
            'pedido_venta_id' => $pedidoId2,
            'producto_id' => $productoG203->id,
            'cantidad' => 20,
            'precio_unitario' => 18.00,
            'impuesto_porcentaje' => 21.00,
            'impuesto_cantidad' => 75.60,
            'descuento' => 0,
            'total' => 435.60,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $albaranId2 = DB::table('albaranes_venta')->insertGetId([
            'pedido_venta_id' => $pedidoId2,
            'numero_albaran' => 'ALB-V-002',
            'fecha_albaran' => '2026-05-05',
            'estado' => 'entregado',
            'subtotal' => 270.00,
            'impuesto' => 56.70,
            'total' => 326.70,
            'observaciones' => 'Entregadas 15 de 20 unidades',
            'usuario_id' => $admin->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $albaranLineaId2 = DB::table('albaranes_venta_linea')->insertGetId([
            'albaran_venta_id' => $albaranId2,
            'pedido_venta_linea_id' => $pedidoLineaId2,
            'producto_id' => $productoG203->id,
            'cantidad_entregada' => 15,
            'cantidad_rechazada' => 5,
            'precio_unitario' => 18.00,
            'impuesto_porcentaje' => 21.00,
            'impuesto_cantidad' => 56.70,
            'total' => 326.70,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('productos')->where('id', $productoG203->id)->decrement('stock_actual', 15);

        $facturaId2 = DB::table('facturas_venta')->insertGetId([
            'albaran_venta_id' => $albaranId2,
            'numero_factura' => 'FAC-V-002',
            'fecha_factura' => '2026-05-06',
            'fecha_vencimiento' => '2026-05-31',
            'estado' => 'abierta',
            'subtotal' => 270.00,
            'impuesto' => 56.70,
            'total' => 326.70,
            'observaciones' => 'Factura parcial - Pendiente de cobro',
            'usuario_id' => $admin->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('facturas_venta_linea')->insert([
            [
                'factura_venta_id' => $facturaId2,
                'albaran_venta_linea_id' => $albaranLineaId2,
                'producto_id' => $productoG203->id,
                'cantidad' => 15,
                'precio_unitario' => 18.00,
                'impuesto_porcentaje' => 21.00,
                'impuesto_cantidad' => 56.70,
                'descuento' => 0,
                'total' => 326.70,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}