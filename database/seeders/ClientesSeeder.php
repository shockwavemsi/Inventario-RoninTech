<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientesSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('👥 Creando clientes de prueba...');

        // CLIENTE 1: Juan García
        DB::table('clientes')->insert([
            'nombre' => 'Juan',
            'apellido' => 'García',
            'documento' => '12345678A',
            'telefono' => '612345678',
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $this->command->line('✅ Cliente 1: Juan García (12345678A)');

        // CLIENTE 2: María López
        DB::table('clientes')->insert([
            'nombre' => 'María',
            'apellido' => 'López',
            'documento' => '87654321B',
            'telefono' => '623456789',
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $this->command->line('✅ Cliente 2: María López (87654321B)');

        // CLIENTE 3: Carlos Martínez
        DB::table('clientes')->insert([
            'nombre' => 'Carlos',
            'apellido' => 'Martínez',
            'documento' => '11223344C',
            'telefono' => '634567890',
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $this->command->line('✅ Cliente 3: Carlos Martínez (11223344C)');

        // CLIENTE 4: Ana Fernández
        DB::table('clientes')->insert([
            'nombre' => 'Ana',
            'apellido' => 'Fernández',
            'documento' => '55667788D',
            'telefono' => '645678901',
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $this->command->line('✅ Cliente 4: Ana Fernández (55667788D)');

        // CLIENTE 5: Pedro Rodríguez
        DB::table('clientes')->insert([
            'nombre' => 'Pedro',
            'apellido' => 'Rodríguez',
            'documento' => '99887766E',
            'telefono' => '656789012',
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $this->command->line('✅ Cliente 5: Pedro Rodríguez (99887766E)');

        $this->command->info('✅ Clientes creados correctamente');
    }
}
        