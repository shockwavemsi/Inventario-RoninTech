<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
     public function run()
    {
        $this->call([
            RolesSeeder::class,      // Crea roles y usuarios
            ProductosSeeder::class,  // Crea productos
            EntradasStockSeeder::class, // Crea entradas
            VentasSeeder::class,     // Crea ventas
            //AlertasSeeder::class, // Opcional
        ]);
        
        $this->command->info('==============================================');
        $this->command->info('BASE DE DATOS INICIALIZADA CORRECTAMENTE');
        $this->command->info('==============================================');
        $this->command->info('Admin: admin@admin.com / admin123');
        $this->command->info('User: user@user.com / user123');
        $this->command->info('==============================================');
    }
}
