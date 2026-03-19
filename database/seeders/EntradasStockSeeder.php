<?php
// database/seeders/EntradasStockSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntradasStockSeeder extends Seeder
{
    public function run()
    {
        // Obtener IDs
        $adminId = DB::table('users')->where('email', 'admin@admin.com')->first()->id;
        $userId = DB::table('users')->where('email', 'user@user.com')->first()->id;

        // Entradas registradas por admin
        DB::table('entradas_stock')->insert([
            [
                'producto_id' => 1, // Ryzen
                'cantidad' => 10,
                'proveedor' => 'DISTEC S.L.',
                'usuario_id' => $adminId,
                'observaciones' => 'Pedido inicial',
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30)
            ],
            [
                'producto_id' => 2, // RTX
                'cantidad' => 5,
                'proveedor' => 'NVIDIA Spain',
                'usuario_id' => $adminId,
                'observaciones' => 'Compra mayorista',
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(25)
            ],
            [
                'producto_id' => 3, // Logitech
                'cantidad' => 20,
                'proveedor' => 'Logitech Iberia',
                'usuario_id' => $adminId,
                'observaciones' => 'Stock tienda',
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20)
            ],
        ]);

        // Entradas registradas por usuario normal
        DB::table('entradas_stock')->insert([
            [
                'producto_id' => 4, // Samsung
                'cantidad' => 8,
                'proveedor' => 'Samsung Electronics',
                'usuario_id' => $userId,
                'observaciones' => 'Reabastecimiento',
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15)
            ],
            [
                'producto_id' => 5, // Corsair
                'cantidad' => 12,
                'proveedor' => 'Corsair Memory',
                'usuario_id' => $userId,
                'observaciones' => 'Nueva remesa',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10)
            ],
            [
                'producto_id' => 1, // Ryzen
                'cantidad' => 5,
                'proveedor' => 'DISTEC S.L.',
                'usuario_id' => $userId,
                'observaciones' => 'Reposición',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5)
            ],
        ]);
    }
}
