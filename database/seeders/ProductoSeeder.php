<?php
// database/seeders/ProductoSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener IDs de categorías y proveedores
        $categoriaProcesadores = DB::table('categorias')->where('nombre', 'Procesadores')->first();
        $categoriaGraficas = DB::table('categorias')->where('nombre', 'Tarjetas Gráficas')->first();
        $categoriaPerifericos = DB::table('categorias')->where('nombre', 'Periféricos')->first();
        $categoriaAlmacenamiento = DB::table('categorias')->where('nombre', 'Almacenamiento')->first();
        $categoriaRam = DB::table('categorias')->where('nombre', 'Memorias RAM')->first();
        
        $proveedorDistec = DB::table('proveedores')->where('nombre', 'DISTEC S.L.')->first();
        $proveedorLogitech = DB::table('proveedores')->where('nombre', 'Logitech Iberia')->first();
        $proveedorAmd = DB::table('proveedores')->where('nombre', 'AMD Direct')->first();
        $proveedorIntel = DB::table('proveedores')->where('nombre', 'Intel Spain')->first();

        $productos = [
            // Procesadores
            [
                'codigo_barras' => '842776112345',
                'sku' => 'RYZ5-5600G',
                'nombre' => 'Ryzen 5 5600G',
                'marca' => 'AMD',
                'modelo' => '5600G',
                'categoria_id' => $categoriaProcesadores->id,
                'proveedor_id' => $proveedorAmd->id,
                'precio_compra' => 180.50,
                'precio_venta' => 249.99,
                'stock_minimo' => 3,
                'stock_maximo' => 20,
                'ubicacion' => 'Estante CPU-1',
                'activo' => true
            ],
            [
                'codigo_barras' => '842776112346',
                'sku' => 'RYZ7-5700X',
                'nombre' => 'Ryzen 7 5700X',
                'marca' => 'AMD',
                'modelo' => '5700X',
                'categoria_id' => $categoriaProcesadores->id,
                'proveedor_id' => $proveedorAmd->id,
                'precio_compra' => 250.00,
                'precio_venta' => 329.99,
                'stock_minimo' => 2,
                'stock_maximo' => 15,
                'ubicacion' => 'Estante CPU-2',
                'activo' => true
            ],
            [
                'codigo_barras' => '843658921015',
                'sku' => 'INTEL-I5-12400F',
                'nombre' => 'Core i5-12400F',
                'marca' => 'Intel',
                'modelo' => '12400F',
                'categoria_id' => $categoriaProcesadores->id,
                'proveedor_id' => $proveedorIntel->id,
                'precio_compra' => 150.00,
                'precio_venta' => 199.99,
                'stock_minimo' => 3,
                'stock_maximo' => 25,
                'ubicacion' => 'Estante CPU-3',
                'activo' => true
            ],
            
            // Tarjetas Gráficas
            [
                'codigo_barras' => '843658921014',
                'sku' => 'RTX4060',
                'nombre' => 'RTX 4060 8GB',
                'marca' => 'NVIDIA',
                'modelo' => 'RTX 4060',
                'categoria_id' => $categoriaGraficas->id,
                'proveedor_id' => $proveedorDistec->id,
                'precio_compra' => 280.00,
                'precio_venta' => 349.99,
                'stock_minimo' => 2,
                'stock_maximo' => 15,
                'ubicacion' => 'Estante GPU-1',
                'activo' => true
            ],
            [
                'codigo_barras' => '843658921016',
                'sku' => 'RX6600',
                'nombre' => 'RX 6600 8GB',
                'marca' => 'AMD',
                'modelo' => 'RX 6600',
                'categoria_id' => $categoriaGraficas->id,
                'proveedor_id' => $proveedorAmd->id,
                'precio_compra' => 220.00,
                'precio_venta' => 279.99,
                'stock_minimo' => 2,
                'stock_maximo' => 15,
                'ubicacion' => 'Estante GPU-2',
                'activo' => true
            ],
            
            // Periféricos
            [
                'codigo_barras' => '841234567890',
                'sku' => 'LOG-G203',
                'nombre' => 'Logitech G203',
                'marca' => 'Logitech',
                'modelo' => 'G203',
                'categoria_id' => $categoriaPerifericos->id,
                'proveedor_id' => $proveedorLogitech->id,
                'precio_compra' => 18.00,
                'precio_venta' => 29.99,
                'stock_minimo' => 5,
                'stock_maximo' => 30,
                'ubicacion' => 'Vitrina 2',
                'activo' => true
            ],
            [
                'codigo_barras' => '841234567891',
                'sku' => 'LOG-G502',
                'nombre' => 'Logitech G502 Hero',
                'marca' => 'Logitech',
                'modelo' => 'G502',
                'categoria_id' => $categoriaPerifericos->id,
                'proveedor_id' => $proveedorLogitech->id,
                'precio_compra' => 35.00,
                'precio_venta' => 49.99,
                'stock_minimo' => 3,
                'stock_maximo' => 20,
                'ubicacion' => 'Vitrina 3',
                'activo' => true
            ],
            
            // Almacenamiento
            [
                'codigo_barras' => '842776112347',
                'sku' => 'SAMSUNG-980-1TB',
                'nombre' => 'Samsung 980 1TB NVMe',
                'marca' => 'Samsung',
                'modelo' => '980',
                'categoria_id' => $categoriaAlmacenamiento->id,
                'proveedor_id' => $proveedorDistec->id,
                'precio_compra' => 65.00,
                'precio_venta' => 89.99,
                'stock_minimo' => 4,
                'stock_maximo' => 25,
                'ubicacion' => 'Estante SSD-1',
                'activo' => true
            ],
            
            // Memorias RAM
            [
                'codigo_barras' => '842776112348',
                'sku' => 'CORSAIR-16GB',
                'nombre' => 'Corsair Vengeance 16GB 3200MHz',
                'marca' => 'Corsair',
                'modelo' => 'Vengeance LPX',
                'categoria_id' => $categoriaRam->id,
                'proveedor_id' => $proveedorDistec->id,
                'precio_compra' => 55.00,
                'precio_venta' => 79.99,
                'stock_minimo' => 4,
                'stock_maximo' => 30,
                'ubicacion' => 'Estante RAM-1',
                'activo' => true
            ],
        ];

        foreach ($productos as $producto) {
            DB::table('productos')->insert([
                'codigo_barras' => $producto['codigo_barras'],
                'sku' => $producto['sku'],
                'nombre' => $producto['nombre'],
                'marca' => $producto['marca'],
                'modelo' => $producto['modelo'],
                'categoria_id' => $producto['categoria_id'],
                'proveedor_id' => $producto['proveedor_id'],
                'precio_compra' => $producto['precio_compra'],
                'precio_venta' => $producto['precio_venta'],
                'stock_minimo' => $producto['stock_minimo'],
                'stock_maximo' => $producto['stock_maximo'],
                'ubicacion' => $producto['ubicacion'],
                'activo' => $producto['activo'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}