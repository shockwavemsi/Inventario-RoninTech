<?php
// database/seeders/VentasSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VentasSeeder extends Seeder
{
    public function run()
    {
        // Obtener IDs
        $adminId = DB::table('users')->where('email', 'admin@admin.com')->first()->id;
        $userId = DB::table('users')->where('email', 'user@user.com')->first()->id;

        $clientes = [
            'Juan Pérez',
            'María García',
            'Carlos López',
            'Ana Martínez',
            'Pedro Sánchez',
            'Laura Fernández',
            'Miguel Rodríguez',
            'Carmen Ruiz'
        ];

        // Ventas registradas
        DB::table('ventas')->insert([
            // Ventas del admin
            [
                'producto_id' => 1, // Ryzen
                'cantidad' => 2,
                'cliente' => 'Juan Pérez',
                'usuario_id' => $adminId,
                'created_at' => now()->subDays(28),
                'updated_at' => now()->subDays(28)
            ],
            [
                'producto_id' => 3, // Logitech
                'cantidad' => 3,
                'cliente' => 'María García',
                'usuario_id' => $adminId,
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(25)
            ],
            [
                'producto_id' => 2, // RTX
                'cantidad' => 1,
                'cliente' => 'Carlos López',
                'usuario_id' => $adminId,
                'created_at' => now()->subDays(22),
                'updated_at' => now()->subDays(22)
            ],

            // Ventas del usuario normal
            [
                'producto_id' => 4, // Samsung
                'cantidad' => 2,
                'cliente' => 'Ana Martínez',
                'usuario_id' => $userId,
                'created_at' => now()->subDays(18),
                'updated_at' => now()->subDays(18)
            ],
            [
                'producto_id' => 1, // Ryzen
                'cantidad' => 1,
                'cliente' => 'Pedro Sánchez',
                'usuario_id' => $userId,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15)
            ],
            [
                'producto_id' => 5, // Corsair
                'cantidad' => 2,
                'cliente' => 'Laura Fernández',
                'usuario_id' => $userId,
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(12)
            ],
            [
                'producto_id' => 3, // Logitech
                'cantidad' => 1,
                'cliente' => 'Miguel Rodríguez',
                'usuario_id' => $userId,
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(8)
            ],
            [
                'producto_id' => 2, // RTX
                'cantidad' => 1,
                'cliente' => 'Carmen Ruiz',
                'usuario_id' => $userId,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3)
            ],
            [
                'producto_id' => 4, // Samsung
                'cantidad' => 1,
                'cliente' => 'Juan Pérez',
                'usuario_id' => $userId,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1)
            ],
        ]);
    }
}
