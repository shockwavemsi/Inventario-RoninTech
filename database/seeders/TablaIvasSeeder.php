<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TablaIvasSeeder extends Seeder
{
    public function run(): void
    {
        $ivas = [
            ['porcentaje' => 0.00, 'descripcion' => 'IVA 0% - Exento'],
            ['porcentaje' => 4.00, 'descripcion' => 'IVA 4% - Reducido'],
            ['porcentaje' => 10.00, 'descripcion' => 'IVA 10% - Reducido'],
            ['porcentaje' => 21.00, 'descripcion' => 'IVA 21% - Normal'],
            ['porcentaje' => 23.00, 'descripcion' => 'IVA 23% - Especial'],
        ];

        foreach ($ivas as $iva) {
            DB::table('tabla_ivas')->insert([
                'porcentaje' => $iva['porcentaje'],
                'descripcion' => $iva['descripcion'],
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $this->command->info('✅ IVAs creados correctamente');
    }
}