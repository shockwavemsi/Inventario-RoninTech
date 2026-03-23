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
            MetodoPagoSeeder::class, // Crear Métodos de pago
            ConfiguracionSeeder::class, // Crear Configuraciones
            CategoriaSeeder::class, // Crear categorías
            ProveedorSeeder::class, // Crear Proveedores
            ProductoSeeder::class, // Crear Productos
            CompraSeeder::class, // Crear Compras
            VentaSeeder::class, // Crear Ventas
        ]);
        
        $this->command->info('==============================================');
        $this->command->info('BASE DE DATOS INICIALIZADA CORRECTAMENTE');
        $this->command->info('==============================================');
        $this->command->info('Admin: admin@admin.com / admin123');
        $this->command->info('User: user@user.com / user123');
        $this->command->info('==============================================');
    }
}
