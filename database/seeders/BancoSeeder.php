<?php

namespace Database\Seeders;

use App\Models\Banco;
use Illuminate\Database\Seeder;

class BancoSeeder extends Seeder
{
    public function run(): void
    {
        $bancos = [
            ['nombre' => 'BBVA', 'codigo_banco' => '0182', 'pais' => 'ES'],
            ['nombre' => 'Santander', 'codigo_banco' => '0049', 'pais' => 'ES'],
            ['nombre' => 'CaixaBank', 'codigo_banco' => '2100', 'pais' => 'ES'],
            ['nombre' => 'Sabadell', 'codigo_banco' => '0081', 'pais' => 'ES'],
            ['nombre' => 'ING Direct', 'codigo_banco' => '0128', 'pais' => 'ES'],
            ['nombre' => 'La Caixa', 'codigo_banco' => '2100', 'pais' => 'ES'],
            ['nombre' => 'HSBC', 'codigo_banco' => '0145', 'pais' => 'ES'],
        ];

        foreach ($bancos as $banco) {
            Banco::firstOrCreate(['nombre' => $banco['nombre']], $banco);
        }

        echo "✅ Bancos creados/actualizados\n";
    }
}