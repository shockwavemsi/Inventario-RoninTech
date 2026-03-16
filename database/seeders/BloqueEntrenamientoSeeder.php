<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BloqueEntrenamientoSeeder extends Seeder
{
    public function run(): void
    {
        //DB::table('bloque_entrenamiento')->truncate();

        DB::table('bloque_entrenamiento')->insert([
            [
                'nombre' => 'Calentamiento',
                'descripcion' => 'Rodaje suave progresivo',
                'tipo' => 'rodaje',
                'duracion_estimada' => '00:15:00',
                'potencia_pct_min' => 55.0,
                'potencia_pct_max' => 65.0,
                'pulso_pct_max' => 70.0,
                'pulso_reserva_pct' => 50.0,
                'comentario' => 'Subir pulsaciones gradualmente'
            ],
            [
                'nombre' => 'Rodaje Z2',
                'descripcion' => 'Resistencia aeróbica',
                'tipo' => 'rodaje',
                'duracion_estimada' => '01:00:00',
                'potencia_pct_min' => 65.0,
                'potencia_pct_max' => 75.0,
                'pulso_pct_max' => 80.0,
                'pulso_reserva_pct' => 65.0,
                'comentario' => 'Base aeróbica'
            ],
            [
                'nombre' => 'Sweet Spot 8 min',
                'descripcion' => 'Intervalos Sweet Spot',
                'tipo' => 'intervalos',
                'duracion_estimada' => '00:08:00',
                'potencia_pct_min' => 88.0,
                'potencia_pct_max' => 94.0,
                'pulso_pct_max' => 90.0,
                'pulso_reserva_pct' => 80.0,
                'comentario' => 'Trabajo de umbral submáximo'
            ],
            [
                'nombre' => 'Recuperación',
                'descripcion' => 'Pedaleo muy suave',
                'tipo' => 'recuperacion',
                'duracion_estimada' => '00:05:00',
                'potencia_pct_min' => 45.0,
                'potencia_pct_max' => 55.0,
                'pulso_pct_max' => 65.0,
                'pulso_reserva_pct' => 45.0,
                'comentario' => 'Eliminar fatiga'
            ],
            [
                'nombre' => 'Enfriamiento',
                'descripcion' => 'Vuelta a la calma',
                'tipo' => 'recuperacion',
                'duracion_estimada' => '00:10:00',
                'potencia_pct_min' => 50.0,
                'potencia_pct_max' => 60.0,
                'pulso_pct_max' => 70.0,
                'pulso_reserva_pct' => 50.0,
                'comentario' => 'Normalizar pulsaciones'
            ],
        ]);
    }
}