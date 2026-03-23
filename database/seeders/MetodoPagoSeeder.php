<?php
// database/seeders/MetodoPagoSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodoPagoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('metodos_pago')->insert([
            ['nombre' => 'efectivo', 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'tarjeta', 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'transferencia', 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'credito', 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}