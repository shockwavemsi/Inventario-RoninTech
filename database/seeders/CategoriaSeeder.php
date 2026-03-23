<?php
// database/seeders/CategoriaSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Procesadores', 'descripcion' => 'CPUs Intel y AMD', 'activo' => true],
            ['nombre' => 'Tarjetas Gráficas', 'descripcion' => 'GPUs NVIDIA y AMD', 'activo' => true],
            ['nombre' => 'Periféricos', 'descripcion' => 'Teclados, ratones, auriculares', 'activo' => true],
            ['nombre' => 'Almacenamiento', 'descripcion' => 'SSD, HDD, NVMe', 'activo' => true],
            ['nombre' => 'Memorias RAM', 'descripcion' => 'DDR4, DDR5', 'activo' => true],
            ['nombre' => 'Placas Base', 'descripcion' => 'Motherboards', 'activo' => true],
            ['nombre' => 'Fuentes de Alimentación', 'descripcion' => 'PSU', 'activo' => true],
        ];

        foreach ($categorias as $categoria) {
            DB::table('categorias')->insert([
                'nombre' => $categoria['nombre'],
                'descripcion' => $categoria['descripcion'],
                'activo' => $categoria['activo'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
