<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComponenteBicicletaSeeder extends Seeder
{
    public function run(): void
    {
        // Tacx NEO2 (id_bicicleta = 1)
        DB::table('componentes_bicicleta')->insert([
            [
                'id_bicicleta' => 1,
                'id_tipo_componente' => 1, // Cadena
                'marca' => 'Shimano',
                'modelo' => 'XT',
                'especificacion' => null,
                'velocidad' => null,
                'posicion' => 'ambas',
                'fecha_montaje' => '2026-01-01',
                'km_actuales' => 0,
                'km_max_recomendado' => 5000,
                'activo' => true
            ],
            [
                'id_bicicleta' => 1,
                'id_tipo_componente' => 7, // Cassette
                'marca' => 'Shimano',
                'modelo' => 'XT',
                'especificacion' => '11-28',
                'velocidad' => '11v',
                'posicion' => 'trasera',
                'fecha_montaje' => '2026-01-01',
                'km_actuales' => 0,
                'km_max_recomendado' => 15000,
                'activo' => true
            ],
            [
                'id_bicicleta' => 1,
                'id_tipo_componente' => 4, // Ruedas
                'marca' => 'Tacx',
                'modelo' => 'NeoWheel',
                'especificacion' => null,
                'velocidad' => null,
                'posicion' => 'ambas',
                'fecha_montaje' => '2026-01-01',
                'km_actuales' => 0,
                'km_max_recomendado' => 20000,
                'activo' => true
            ],
        ]);

        // Stevens A (id_bicicleta = 2)
        DB::table('componentes_bicicleta')->insert([
            [
                'id_bicicleta' => 2,
                'id_tipo_componente' => 1, // Cadena
                'marca' => 'Shimano',
                'modelo' => 'Durace',
                'especificacion' => null,
                'velocidad' => null,
                'posicion' => 'ambas',
                'fecha_montaje' => '2026-01-01',
                'km_actuales' => 0,
                'km_max_recomendado' => 4000,
                'activo' => true
            ],
            [
                'id_bicicleta' => 2,
                'id_tipo_componente' => 7, // Cassette
                'marca' => 'Shimano',
                'modelo' => 'Durace',
                'especificacion' => '11-25',
                'velocidad' => '11v',
                'posicion' => 'trasera',
                'fecha_montaje' => '2026-01-01',
                'km_actuales' => 0,
                'km_max_recomendado' => 12000,
                'activo' => true
            ],
            [
                'id_bicicleta' => 2,
                'id_tipo_componente' => 4, // Ruedas
                'marca' => 'Campagnolo',
                'modelo' => 'Shamal',
                'especificacion' => '700c',
                'velocidad' => null,
                'posicion' => 'ambas',
                'fecha_montaje' => '2026-01-01',
                'km_actuales' => 0,
                'km_max_recomendado' => 20000,
                'activo' => true
            ],
        ]);

        // Stevens B (id_bicicleta = 3)
        DB::table('componentes_bicicleta')->insert([
            [
                'id_bicicleta' => 3,
                'id_tipo_componente' => 1, // Cadena
                'marca' => 'Shimano',
                'modelo' => 'Durace',
                'especificacion' => null,
                'velocidad' => null,
                'posicion' => 'ambas',
                'fecha_montaje' => '2026-01-01',
                'km_actuales' => 0,
                'km_max_recomendado' => 4000,
                'activo' => true
            ],
            [
                'id_bicicleta' => 3,
                'id_tipo_componente' => 7, // Cassette
                'marca' => 'Shimano',
                'modelo' => 'Durace',
                'especificacion' => '11-28',
                'velocidad' => '11v',
                'posicion' => 'trasera',
                'fecha_montaje' => '2026-01-01',
                'km_actuales' => 0,
                'km_max_recomendado' => 12000,
                'activo' => true
            ],
            [
                'id_bicicleta' => 3,
                'id_tipo_componente' => 4, // Ruedas
                'marca' => 'Velozer',
                'modelo' => 'Tubular',
                'especificacion' => '700c',
                'velocidad' => null,
                'posicion' => 'ambas',
                'fecha_montaje' => '2026-01-01',
                'km_actuales' => 0,
                'km_max_recomendado' => 20000,
                'activo' => true
            ],
        ]);

        // Kuota (id_bicicleta = 4)
        DB::table('componentes_bicicleta')->insert([
            [
                'id_bicicleta' => 4,
                'id_tipo_componente' => 1, // Cadena
                'marca' => 'Shimano',
                'modelo' => 'Tiagra',
                'especificacion' => null,
                'velocidad' => null,
                'posicion' => 'ambas',
                'fecha_montaje' => '2026-01-01',
                'km_actuales' => 0,
                'km_max_recomendado' => 3500,
                'activo' => true
            ],
            [
                'id_bicicleta' => 4,
                'id_tipo_componente' => 7, // Cassette
                'marca' => 'Shimano',
                'modelo' => 'Tiagra',
                'especificacion' => '12-28',
                'velocidad' => '10v',
                'posicion' => 'trasera',
                'fecha_montaje' => '2026-01-01',
                'km_actuales' => 0,
                'km_max_recomendado' => 10000,
                'activo' => true
            ],
            [
                'id_bicicleta' => 4,
                'id_tipo_componente' => 4, // Ruedas
                'marca' => 'Mavic',
                'modelo' => 'Ksyrium',
                'especificacion' => '700c',
                'velocidad' => null,
                'posicion' => 'ambas',
                'fecha_montaje' => '2026-01-01',
                'km_actuales' => 0,
                'km_max_recomendado' => 20000,
                'activo' => true
            ],
        ]);

        // MTB (id_bicicleta = 5)
        DB::table('componentes_bicicleta')->insert([
            [
                'id_bicicleta' => 5,
                'id_tipo_componente' => 1, // Cadena
                'marca' => 'Shimano',
                'modelo' => 'Alivio',
                'especificacion' => null,
                'velocidad' => null,
                'posicion' => 'ambas',
                'fecha_montaje' => '2026-01-01',
                'km_actuales' => 0,
                'km_max_recomendado' => 3000,
                'activo' => true
            ],
            [
                'id_bicicleta' => 5,
                'id_tipo_componente' => 7, // Cassette
                'marca' => 'Shimano',
                'modelo' => 'Alivio',
                'especificacion' => '32-12',
                'velocidad' => '9v',
                'posicion' => 'trasera',
                'fecha_montaje' => '2026-01-01',
                'km_actuales' => 0,
                'km_max_recomendado' => 10000,
                'activo' => true
            ],
            [
                'id_bicicleta' => 5,
                'id_tipo_componente' => 4, // Ruedas
                'marca' => 'Diamondback',
                'modelo' => '26',
                'especificacion' => '26',
                'velocidad' => null,
                'posicion' => 'ambas',
                'fecha_montaje' => '2026-01-01',
                'km_actuales' => 0,
                'km_max_recomendado' => 20000,
                'activo' => true
            ],
        ]);

        // MTB Electrica (id_bicicleta = 6)
        DB::table('componentes_bicicleta')->insert([
            [
                'id_bicicleta' => 6,
                'id_tipo_componente' => 1, // Cadena
                'marca' => 'Shimano',
                'modelo' => 'Alivio',
                'especificacion' => null,
                'velocidad' => null,
                'posicion' => 'ambas',
                'fecha_montaje' => '2026-01-01',
                'km_actuales' => 0,
                'km_max_recomendado' => 3000,
                'activo' => true
            ],
            [
                'id_bicicleta' => 6,
                'id_tipo_componente' => 7, // Cassette
                'marca' => 'Shimano',
                'modelo' => 'Alivio',
                'especificacion' => '32-12',
                'velocidad' => '9v',
                'posicion' => 'trasera',
                'fecha_montaje' => '2026-01-01',
                'km_actuales' => 0,
                'km_max_recomendado' => 10000,
                'activo' => true
            ],
            [
                'id_bicicleta' => 6,
                'id_tipo_componente' => 4, // Ruedas
                'marca' => 'Diamondback',
                'modelo' => '26',
                'especificacion' => '26',
                'velocidad' => null,
                'posicion' => 'ambas',
                'fecha_montaje' => '2026-01-01',
                'km_actuales' => 0,
                'km_max_recomendado' => 20000,
                'activo' => true
            ],
        ]);
    }
}
