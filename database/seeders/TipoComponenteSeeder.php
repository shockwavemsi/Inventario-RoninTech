<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoComponenteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipo_componente')->insert([
            [
                'nombre' => 'Cadena',
                'descripcion' => 'Cadena de la bicicleta'
            ],
            [
                'nombre' => 'Bielas',
                'descripcion' => 'Bielas del pedalier'
            ],
            [
                'nombre' => 'Pedales',
                'descripcion' => 'Pedales de la bicicleta'
            ],
            [
                'nombre' => 'Ruedas',
                'descripcion' => 'Juego de ruedas completo'
            ],
            [
                'nombre' => 'Sillín',
                'descripcion' => 'Sillín o asiento'
            ],
            [
                'nombre' => 'Manillar',
                'descripcion' => 'Manillar y potencia'
            ],
            [
                'nombre' => 'Cassette',
                'descripcion' => 'Piñón o conjunto de piñones trasero'
            ],
        ]);
    }
}
