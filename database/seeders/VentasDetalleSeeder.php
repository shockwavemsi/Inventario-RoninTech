<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VentasDetalleSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📦 Creando líneas de ventas...');

        // Obtener productos
        $productos = DB::table('productos')->get();
        $productoRtx = $productos->where('nombre', 'RTX 4060 8GB')->first();
        $productoG203 = $productos->where('nombre', 'Logitech G203')->first();
        $productoProcesador = $productos->first(); // Primer producto como alternativa

        // ✅ VENTA 1 - 3 PRODUCTOS
        $venta1 = DB::table('ventas')->where('numero_factura', 'FAC-V-001')->first();

        if ($venta1) {
            $lineas1 = [
                [
                    'venta_id' => $venta1->id,
                    'producto_id' => $productoRtx->id ?? $productos->first()->id,
                    'cantidad' => 2,
                    'precio_unitario' => 280.00,
                    'subtotal' => 560.00,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'venta_id' => $venta1->id,
                    'producto_id' => $productoG203->id ?? $productos->skip(1)->first()->id,
                    'cantidad' => 5,
                    'precio_unitario' => 18.00,
                    'subtotal' => 90.00,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'venta_id' => $venta1->id,
                    'producto_id' => $productos->skip(2)->first()->id ?? $productoProcesador->id,
                    'cantidad' => 3,
                    'precio_unitario' => 150.00,
                    'subtotal' => 450.00,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];

            DB::table('ventas_detalle')->insert($lineas1);

            // Calcular totales de Venta 1
            $subtotal1 = 560 + 90 + 450; // 1100.00
            $impuesto1 = $subtotal1 * 0.21; // 231.00
            $total1 = $subtotal1 + $impuesto1; // 1331.00

            DB::table('ventas')
                ->where('id', $venta1->id)
                ->update([
                    'subtotal' => $subtotal1,
                    'impuesto' => $impuesto1,
                    'total' => $total1
                ]);

            $this->command->line("✅ Venta 1: 3 líneas | 2x RTX + 5x G203 + 3x Otros = {$total1}€");
        }

        // ✅ VENTA 2 - 2 PRODUCTOS
        $venta2 = DB::table('ventas')->where('numero_factura', 'FAC-V-002')->first();

        if ($venta2) {
            $lineas2 = [
                [
                    'venta_id' => $venta2->id,
                    'producto_id' => $productoG203->id ?? $productos->first()->id,
                    'cantidad' => 10,
                    'precio_unitario' => 18.00,
                    'subtotal' => 180.00,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'venta_id' => $venta2->id,
                    'producto_id' => $productos->skip(1)->first()->id,
                    'cantidad' => 4,
                    'precio_unitario' => 75.00,
                    'subtotal' => 300.00,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];

            DB::table('ventas_detalle')->insert($lineas2);

            // Calcular totales de Venta 2
            $subtotal2 = 180 + 300; // 480.00
            $impuesto2 = $subtotal2 * 0.21; // 100.80
            $total2 = $subtotal2 + $impuesto2; // 580.80

            DB::table('ventas')
                ->where('id', $venta2->id)
                ->update([
                    'subtotal' => $subtotal2,
                    'impuesto' => $impuesto2,
                    'total' => $total2
                ]);

            $this->command->line("✅ Venta 2: 2 líneas | 10x G203 + 4x Otros = {$total2}€");
        }

        // ✅ VENTA 3 - 4 PRODUCTOS (Mostrador)
        $venta3 = DB::table('ventas')->where('numero_factura', 'FAC-V-003')->first();

        if ($venta3) {
            $lineas3 = [
                [
                    'venta_id' => $venta3->id,
                    'producto_id' => $productoG203->id ?? $productos->first()->id,
                    'cantidad' => 3,
                    'precio_unitario' => 18.00,
                    'subtotal' => 54.00,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'venta_id' => $venta3->id,
                    'producto_id' => $productos->skip(1)->first()->id,
                    'cantidad' => 2,
                    'precio_unitario' => 45.00,
                    'subtotal' => 90.00,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'venta_id' => $venta3->id,
                    'producto_id' => $productos->skip(2)->first()->id,
                    'cantidad' => 1,
                    'precio_unitario' => 120.00,
                    'subtotal' => 120.00,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'venta_id' => $venta3->id,
                    'producto_id' => $productos->skip(3)->first()->id,
                    'cantidad' => 5,
                    'precio_unitario' => 22.00,
                    'subtotal' => 110.00,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];

            DB::table('ventas_detalle')->insert($lineas3);

            // Calcular totales de Venta 3
            $subtotal3 = 54 + 90 + 120 + 110; // 374.00
            $impuesto3 = $subtotal3 * 0.21; // 78.54
            $total3 = $subtotal3 + $impuesto3; // 452.54

            DB::table('ventas')
                ->where('id', $venta3->id)
                ->update([
                    'subtotal' => $subtotal3,
                    'impuesto' => $impuesto3,
                    'total' => $total3
                ]);

            $this->command->line("✅ Venta 3: 4 líneas | 3x G203 + 2x + 1x + 5x = {$total3}€");
        }

        $this->command->info('✅ Líneas de ventas creadas correctamente');
    }
}