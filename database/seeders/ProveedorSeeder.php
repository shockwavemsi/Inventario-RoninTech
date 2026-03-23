<?php
// database/seeders/ProveedorSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProveedorSeeder extends Seeder
{
    public function run(): void
    {
        $proveedores = [
            [
                'nombre' => 'DISTEC S.L.',
                'ruc' => 'B12345678',
                'telefono' => '912345678',
                'email' => 'ventas@distec.com',
                'direccion' => 'C/ Mayor 123, Madrid',
                'contacto_nombre' => 'Juan Pérez',
                'contacto_telefono' => '611223344',
                'activo' => true
            ],
            [
                'nombre' => 'PC Componentes',
                'ruc' => 'B87654321',
                'telefono' => '968123456',
                'email' => 'info@pccomponentes.com',
                'direccion' => 'Pol. Ind. La Polvorista, Murcia',
                'contacto_nombre' => 'María García',
                'contacto_telefono' => '699887766',
                'activo' => true
            ],
            [
                'nombre' => 'Logitech Iberia',
                'ruc' => 'B11223344',
                'telefono' => '932345678',
                'email' => 'spain@logitech.com',
                'direccion' => 'C/ Industria 45, Barcelona',
                'contacto_nombre' => 'Carlos López',
                'contacto_telefono' => '655443322',
                'activo' => true
            ],
            [
                'nombre' => 'AMD Direct',
                'ruc' => 'B99887766',
                'telefono' => '911223344',
                'email' => 'sales@amd.com',
                'direccion' => 'C/ Tecnología 50, Madrid',
                'activo' => true
            ],
            [
                'nombre' => 'Intel Spain',
                'ruc' => 'B55667788',
                'telefono' => '933445566',
                'email' => 'spain@intel.com',
                'direccion' => 'Av. Diagonal 600, Barcelona',
                'activo' => true
            ],
        ];

        foreach ($proveedores as $proveedor) {
            DB::table('proveedores')->insert([
                'nombre' => $proveedor['nombre'],
                'ruc' => $proveedor['ruc'] ?? null,
                'telefono' => $proveedor['telefono'] ?? null,
                'email' => $proveedor['email'] ?? null,
                'direccion' => $proveedor['direccion'] ?? null,
                'contacto_nombre' => $proveedor['contacto_nombre'] ?? null,
                'contacto_telefono' => $proveedor['contacto_telefono'] ?? null,
                'activo' => $proveedor['activo'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}