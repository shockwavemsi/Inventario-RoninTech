<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HistoricoCiclistaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('historico_ciclista')->insert([
            [
                'id_ciclista' => 1,
                'fecha' => '2026-01-01',
                'peso' => 72.5,
                'ftp' => 280,
                'pulso_max' => 185,
                'pulso_reposo' => 48,
                'potencia_max' => 1050,
                'grasa_corporal' => 14.5,
                'vo2max' => 62.3,
                'comentario' => 'Inicio de temporada'
            ],
            [
                'id_ciclista' => 1,
                'fecha' => '2026-02-01',
                'peso' => 71.8,
                'ftp' => 290,
                'pulso_max' => 185,
                'pulso_reposo' => 46,
                'potencia_max' => 1100,
                'grasa_corporal' => 14.0,
                'vo2max' => 63.5,
                'comentario' => 'Mejora tras bloque base'
            ],
            [
                'id_ciclista' => 1,
                'fecha' => '2026-03-01',
                'peso' => 70.9,
                'ftp' => 300,
                'pulso_max' => 186,
                'pulso_reposo' => 45,
                'potencia_max' => 1150,
                'grasa_corporal' => 13.6,
                'vo2max' => 65.0,
                'comentario' => 'Pico de forma'
            ],
            [
                'id_ciclista' => 2,
                'fecha' => '2026-01-15',
                'peso' => 78.2,
                'ftp' => 250,
                'pulso_max' => 180,
                'pulso_reposo' => 52,
                'potencia_max' => 980,
                'grasa_corporal' => 16.8,
                'vo2max' => 58.0,
                'comentario' => 'Inicio plan umbral'
            ],
            [
                'id_ciclista' => 2,
                'fecha' => '2026-02-15',
                'peso' => 77.5,
                'ftp' => 265,
                'pulso_max' => 181,
                'pulso_reposo' => 50,
                'potencia_max' => 1020,
                'grasa_corporal' => 16.0,
                'vo2max' => 59.5,
                'comentario' => 'Mejora progresiva'
            ],
        ]);
    }
}