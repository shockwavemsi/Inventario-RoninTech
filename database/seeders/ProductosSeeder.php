<?php
// database/seeders/ProductosSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosSeeder extends Seeder
{
    public function run()
    {
        DB::table('productos')->insert([
            [
                'nombre' => 'Ryzen 5 5600G',
                'marca' => 'AMD',
                'precio_venta' => 249.99,
                'stock_minimo' => 3,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'RTX 4060',
                'marca' => 'NVIDIA',
                'precio_venta' => 349.99,
                'stock_minimo' => 2,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Logitech G203',
                'marca' => 'Logitech',
                'precio_venta' => 29.99,
                'stock_minimo' => 5,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Samsung 980 1TB',
                'marca' => 'Samsung',
                'precio_venta' => 89.99,
                'stock_minimo' => 4,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Corsair Vengeance 16GB',
                'marca' => 'Corsair',
                'precio_venta' => 79.99,
                'stock_minimo' => 4,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
