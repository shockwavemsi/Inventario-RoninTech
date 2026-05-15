<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $categoriaProcesadores = DB::table('categorias')->where('nombre', 'Procesadores')->first();
        $categoriaGraficas = DB::table('categorias')->where('nombre', 'Tarjetas Gráficas')->first();
        $categoriaPerifericos = DB::table('categorias')->where('nombre', 'Periféricos')->first();
        $categoriaAlmacenamiento = DB::table('categorias')->where('nombre', 'Almacenamiento')->first();
        $categoriaRam = DB::table('categorias')->where('nombre', 'Memorias RAM')->first();

        $proveedorDistec = DB::table('proveedores')->where('nombre', 'DISTEC S.L.')->first();
        $proveedorLogitech = DB::table('proveedores')->where('nombre', 'Logitech Iberia')->first();
        $proveedorAmd = DB::table('proveedores')->where('nombre', 'AMD Direct')->first();
        $proveedorIntel = DB::table('proveedores')->where('nombre', 'Intel Spain')->first();
        $proveedorPc = DB::table('proveedores')->where('nombre', 'PC Componentes')->first();

        $productos = [
            // Procesadores: 21%
            [
                'nombre' => 'Ryzen 5 5600G',
                'marca' => 'AMD',
                'modelo' => '5600G',
                'categoria_id' => $categoriaProcesadores->id,
                'proveedor_id' => $proveedorAmd->id,
                'precio_base_compra' => 180.50,
                'porcentaje_iva_compra' => 21.00,
                'precio_base_venta' => 249.99,
                'porcentaje_iva_venta' => 21.00,
                'stock_minimo' => 3,
                'stock_maximo' => 20,
                'ubicacion' => 'Estante CPU-1',
                'activo' => true
            ],
            [
                'nombre' => 'Ryzen 7 5700X',
                'marca' => 'AMD',
                'modelo' => '5700X',
                'categoria_id' => $categoriaProcesadores->id,
                'proveedor_id' => $proveedorAmd->id,
                'precio_base_compra' => 250.00,
                'porcentaje_iva_compra' => 21.00,
                'precio_base_venta' => 329.99,
                'porcentaje_iva_venta' => 21.00,
                'stock_minimo' => 2,
                'stock_maximo' => 15,
                'ubicacion' => 'Estante CPU-2',
                'activo' => true
            ],
            [
                'nombre' => 'Core i5-12400F',
                'marca' => 'Intel',
                'modelo' => '12400F',
                'categoria_id' => $categoriaProcesadores->id,
                'proveedor_id' => $proveedorIntel->id,
                'precio_base_compra' => 150.00,
                'porcentaje_iva_compra' => 21.00,
                'precio_base_venta' => 199.99,
                'porcentaje_iva_venta' => 21.00,
                'stock_minimo' => 3,
                'stock_maximo' => 25,
                'ubicacion' => 'Estante CPU-3',
                'activo' => true
            ],

            // Tarjetas Gráficas: 21%
            [
                'nombre' => 'RTX 4060 8GB',
                'marca' => 'NVIDIA',
                'modelo' => 'RTX 4060',
                'categoria_id' => $categoriaGraficas->id,
                'proveedor_id' => $proveedorDistec->id,
                'precio_base_compra' => 280.00,
                'porcentaje_iva_compra' => 21.00,
                'precio_base_venta' => 349.99,
                'porcentaje_iva_venta' => 21.00,
                'stock_minimo' => 2,
                'stock_maximo' => 15,
                'ubicacion' => 'Estante GPU-1',
                'activo' => true
            ],
            [
                'nombre' => 'RX 6600 8GB',
                'marca' => 'AMD',
                'modelo' => 'RX 6600',
                'categoria_id' => $categoriaGraficas->id,
                'proveedor_id' => $proveedorAmd->id,
                'precio_base_compra' => 220.00,
                'porcentaje_iva_compra' => 21.00,
                'precio_base_venta' => 279.99,
                'porcentaje_iva_venta' => 21.00,
                'stock_minimo' => 2,
                'stock_maximo' => 15,
                'ubicacion' => 'Estante GPU-2',
                'activo' => true
            ],

            // Periféricos: 0% (EXENTO)
            [
                'nombre' => 'Logitech G203',
                'marca' => 'Logitech',
                'modelo' => 'G203',
                'categoria_id' => $categoriaPerifericos->id,
                'proveedor_id' => $proveedorLogitech->id,
                'precio_base_compra' => 18.00,
                'porcentaje_iva_compra' => 0.00,
                'precio_base_venta' => 29.99,
                'porcentaje_iva_venta' => 0.00,
                'stock_minimo' => 5,
                'stock_maximo' => 30,
                'ubicacion' => 'Vitrina 2',
                'activo' => true
            ],
            [
                'nombre' => 'Logitech G502 Hero',
                'marca' => 'Logitech',
                'modelo' => 'G502',
                'categoria_id' => $categoriaPerifericos->id,
                'proveedor_id' => $proveedorLogitech->id,
                'precio_base_compra' => 35.00,
                'porcentaje_iva_compra' => 0.00,
                'precio_base_venta' => 49.99,
                'porcentaje_iva_venta' => 0.00,
                'stock_minimo' => 3,
                'stock_maximo' => 20,
                'ubicacion' => 'Vitrina 3',
                'activo' => true
            ],

            // Almacenamiento: 23%
            [
                'nombre' => 'Samsung 980 1TB NVMe',
                'marca' => 'Samsung',
                'modelo' => '980',
                'categoria_id' => $categoriaAlmacenamiento->id,
                'proveedor_id' => $proveedorDistec->id,
                'precio_base_compra' => 65.00,
                'porcentaje_iva_compra' => 23.00,
                'precio_base_venta' => 89.99,
                'porcentaje_iva_venta' => 23.00,
                'stock_minimo' => 4,
                'stock_maximo' => 25,
                'ubicacion' => 'Estante SSD-1',
                'activo' => true
            ],
            [
                'nombre' => 'Kingston A2000 500GB',
                'marca' => 'Kingston',
                'modelo' => 'A2000',
                'categoria_id' => $categoriaAlmacenamiento->id,
                'proveedor_id' => $proveedorPc->id,
                'precio_base_compra' => 35.00,
                'porcentaje_iva_compra' => 23.00,
                'precio_base_venta' => 55.99,
                'porcentaje_iva_venta' => 23.00,
                'stock_minimo' => 5,
                'stock_maximo' => 30,
                'ubicacion' => 'Estante SSD-2',
                'activo' => true
            ],

            // Memorias RAM: 23%
            [
                'nombre' => 'Corsair Vengeance 16GB 3200MHz',
                'marca' => 'Corsair',
                'modelo' => 'Vengeance LPX',
                'categoria_id' => $categoriaRam->id,
                'proveedor_id' => $proveedorDistec->id,
                'precio_base_compra' => 55.00,
                'porcentaje_iva_compra' => 23.00,
                'precio_base_venta' => 79.99,
                'porcentaje_iva_venta' => 23.00,
                'stock_minimo' => 4,
                'stock_maximo' => 30,
                'ubicacion' => 'Estante RAM-1',
                'activo' => true
            ],
            [
                'nombre' => 'Kingston Fury 32GB 5200MHz',
                'marca' => 'Kingston',
                'modelo' => 'Fury Beast',
                'categoria_id' => $categoriaRam->id,
                'proveedor_id' => $proveedorPc->id,
                'precio_base_compra' => 95.00,
                'porcentaje_iva_compra' => 23.00,
                'precio_base_venta' => 139.99,
                'porcentaje_iva_venta' => 23.00,
                'stock_minimo' => 2,
                'stock_maximo' => 15,
                'ubicacion' => 'Estante RAM-2',
                'activo' => true
            ],
        ];

        foreach ($productos as $producto) {
    $precio_compra_final = $producto['precio_base_compra'] * (1 + ($producto['porcentaje_iva_compra'] / 100));
    $precio_venta_final = $producto['precio_base_venta'] * (1 + ($producto['porcentaje_iva_venta'] / 100));

    // ✅ CALCULAR STOCK INICIAL (entre mínimo y máximo)
    $stock_inicial = rand($producto['stock_minimo'], $producto['stock_maximo']);

    DB::table('productos')->insert([
        'nombre' => $producto['nombre'],
        'marca' => $producto['marca'],
        'modelo' => $producto['modelo'],
        'categoria_id' => $producto['categoria_id'],
        'proveedor_id' => $producto['proveedor_id'],
        'precio_base_compra' => $producto['precio_base_compra'],
        'porcentaje_iva_compra' => $producto['porcentaje_iva_compra'],
        'precio_compra_final' => round($precio_compra_final, 2),
        'precio_base_venta' => $producto['precio_base_venta'],
        'porcentaje_iva_venta' => $producto['porcentaje_iva_venta'],
        'precio_venta_final' => round($precio_venta_final, 2),
        'stock_actual' => $stock_inicial,  // ✅ STOCK ALEATORIO
        'stock_minimo' => $producto['stock_minimo'],
        'stock_maximo' => $producto['stock_maximo'],
        'ubicacion' => $producto['ubicacion'],
        'activo' => $producto['activo'],
        'created_at' => now(),
        'updated_at' => now()
    ]);
}

        $this->command->info('✅ Productos creados con stocks iniciales variados');
    }
}
