<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SesionBloqueSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sesion_bloque')->truncate();

        DB::table('sesion_bloque')->insert([
            // Bloques para sesiones del ciclista 1 (plan 1)
            [
                'id_sesion_entrenamiento' => 1,
                'id_bloque_entrenamiento' => 1,
                'orden' => 1,
                'repeticiones' => 1
            ],
            [
                'id_sesion_entrenamiento' => 1,
                'id_bloque_entrenamiento' => 2,
                'orden' => 2,
                'repeticiones' => 1
            ],
            [
                'id_sesion_entrenamiento' => 1,
                'id_bloque_entrenamiento' => 5,
                'orden' => 3,
                'repeticiones' => 1
            ],
            [
                'id_sesion_entrenamiento' => 2,
                'id_bloque_entrenamiento' => 1,
                'orden' => 1,
                'repeticiones' => 1
            ],
            [
                'id_sesion_entrenamiento' => 2,
                'id_bloque_entrenamiento' => 3,
                'orden' => 2,
                'repeticiones' => 4
            ],
            [
                'id_sesion_entrenamiento' => 2,
                'id_bloque_entrenamiento' => 4,
                'orden' => 3,
                'repeticiones' => 3
            ],
            [
                'id_sesion_entrenamiento' => 2,
                'id_bloque_entrenamiento' => 5,
                'orden' => 4,
                'repeticiones' => 1
            ],

            // 🔥 NUEVOS BLOQUES PARA EL CICLISTA 2 (plan 2 → sesión 3)
            [
                'id_sesion_entrenamiento' => 3,
                'id_bloque_entrenamiento' => 1,
                'orden' => 1,
                'repeticiones' => 2
            ],
            [
                'id_sesion_entrenamiento' => 3,
                'id_bloque_entrenamiento' => 4,
                'orden' => 2,
                'repeticiones' => 3
            ],
            [
                'id_sesion_entrenamiento' => 3,
                'id_bloque_entrenamiento' => 5,
                'orden' => 3,
                'repeticiones' => 1
            ],
        ]);
    }
}
