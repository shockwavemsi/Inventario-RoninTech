<?php
// database/seeders/ConfiguracionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfiguracionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('configuracion')->insert([
            'nombre_empresa' => 'RoninTech',
            'ruc' => 'B87654321',
            'telefono' => '912345678',
            'email' => 'info@ronintech.com',
            'direccion' => 'C/ Tecnología 123, Madrid',
            'impuesto_porcentaje' => 21.00,
            'moneda' => 'EUR',
            'formato_factura' => 'FACT-{NRO}',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}