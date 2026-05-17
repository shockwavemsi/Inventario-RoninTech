<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n🚀 ========== INICIANDO SEEDERS ==========\n\n";

        // ===== BASE =====
        echo "📋 Cargando configuración...\n";
        $this->call([
            ConfiguracionSeeder::class,
            RolesSeeder::class,
        ]);

        // ===== DATOS MAESTROS =====
        echo "📦 Cargando datos maestros...\n";
        $this->call([
            TablaIvasSeeder::class, 
            CategoriaSeeder::class,
            ProveedorSeeder::class,
            ProductoSeeder::class,
            DiasVencimientoProveedorSeeder::class,
        ]);

        // ===== FORMAS DE PAGO =====
        echo "💳 Cargando formas de pago...\n";
        $this->call([
            ClientesSeeder::class,
            VentasSeeder::class,
            VentasDetalleSeeder::class,
            BancoSeeder::class,
            FormaPagoSeeder::class,
            FormasPagoProveedorSeeder::class,
        ]);

        // ===== COMPRA =====
        echo "🛒 Cargando datos de compra...\n";
        $this->call([
            PedidosCompraSeeder::class,
            PedidosCompraLineaSeeder::class,
            AlbaranesCompraSeeder::class,
            AlbaranesCompraLineaSeeder::class,
            FacturasCompraSeeder::class,
            FacturasCompraLineaSeeder::class,
            PagoFacturaSeeder::class,
        ]);

        // ===== VENTA (COMENTADA - ACTIVAR DESPUÉS) =====
        // echo "📊 Cargando datos de venta...\n";
        // $this->call([
        //     PedidosVentaSeeder::class,
        //     PedidosVentaLineaSeeder::class,
        //     AlbaranesVentaSeeder::class,
        //     AlbaranesVentaLineaSeeder::class,
        //     FacturasVentaSeeder::class,
        //     FacturasVentaLineaSeeder::class,
        // ]);

        echo "\n✅ ========== SEEDERS COMPLETADOS ==========\n\n";
    }
}