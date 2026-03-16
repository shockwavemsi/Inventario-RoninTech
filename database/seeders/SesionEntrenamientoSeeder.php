<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SesionEntrenamientoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sesion_entrenamientos')->insert([
            [
                'id_plan' => 1,
                'fecha' => '2026-01-14',
                'nombre' => 'Intervalos de tempo',
                'descripcion' => 'Trabajo sostenido al 85% del FTP',
                'completada' => 0,
            ],
            [
                'id_plan' => 1,
                'fecha' => '2026-01-16',
                'nombre' => 'Rodaje suave',
                'descripcion' => 'Sesión de recuperación aeróbica',
                'completada' => 1,
            ],
            [
                'id_plan' => 1,
                'fecha' => '2026-01-18',
                'nombre' => 'Fuerza en llano',
                'descripcion' => 'Desarrollo de potencia a baja cadencia',
                'completada' => 0,
            ],
            [
                'id_plan' => 1,
                'fecha' => '2026-01-19',
                'nombre' => 'Series de sprint',
                'descripcion' => 'Repeticiones cortas de máxima intensidad',
                'completada' => 1,
            ],
            [
                'id_plan' => 1,
                'fecha' => '2026-01-21',
                'nombre' => 'Rodaje progresivo',
                'descripcion' => 'Incremento gradual de intensidad',
                'completada' => 0,
            ],
            [
                'id_plan' => 1,
                'fecha' => '2026-01-23',
                'nombre' => 'Sweet Spot extendido',
                'descripcion' => 'Intervalos largos al 90% del FTP',
                'completada' => 1,
            ],
            [
                'id_plan' => 1,
                'fecha' => '2026-01-24',
                'nombre' => 'Técnica de pedaleo',
                'descripcion' => 'Mejora de eficiencia y cadencia',
                'completada' => 0,
            ],
            [
                'id_plan' => 1,
                'fecha' => '2026-01-26',
                'nombre' => 'VO2Max piramidal',
                'descripcion' => 'Bloques crecientes y decrecientes de intensidad',
                'completada' => 1,
            ],
            [
                'id_plan' => 1,
                'fecha' => '2026-01-28',
                'nombre' => 'Rodaje regenerativo',
                'descripcion' => 'Sesión muy suave para recuperar',
                'completada' => 1,
            ],
            [
                'id_plan' => 1,
                'fecha' => '2026-01-29',
                'nombre' => 'Umbral variable',
                'descripcion' => 'Cambios de ritmo alrededor del FTP',
                'completada' => 0,
            ],
            [
                'id_plan' => 1,
                'fecha' => '2026-02-01',
                'nombre' => 'Tirada larga en zona 2',
                'descripcion' => 'Resistencia aeróbica prolongada',
                'completada' => 1,
            ],
            [
                'id_plan' => 1,
                'fecha' => '2026-02-03',
                'nombre' => 'Intervalos de cadencia',
                'descripcion' => 'Trabajo alternando cadencias altas y bajas',
                'completada' => 0,
            ],
            [
                'id_plan' => 1,
                'fecha' => '2026-02-05',
                'nombre' => 'Fuerza en subida',
                'descripcion' => 'Simulación de cuestas largas',
                'completada' => 1,
            ],
            [
                'id_plan' => 1,
                'fecha' => '2026-02-07',
                'nombre' => 'Rodaje aeróbico medio',
                'descripcion' => 'Sesión continua en zona 2-3',
                'completada' => 1,
            ],
            [
                'id_plan' => 1,
                'fecha' => '2026-02-09',
                'nombre' => 'Series de potencia',
                'descripcion' => 'Esfuerzos cortos por encima del FTP',
                'completada' => 0,
            ],
            [
                'id_plan' => 1,
                'fecha' => '2026-02-11',
                'nombre' => 'Recuperación activa',
                'descripcion' => 'Rodaje suave para eliminar fatiga',
                'completada' => 1,
            ],
            [
                'id_plan' => 1,
                'fecha' => '2026-02-13',
                'nombre' => 'Sweet Spot con descansos cortos',
                'descripcion' => 'Intervalos exigentes con pausas breves',
                'completada' => 0,
            ],
            [
                'id_plan' => 1,
                'fecha' => '2026-02-15',
                'nombre' => 'Rodaje constante',
                'descripcion' => 'Sesión estable sin cambios de ritmo',
                'completada' => 1,
            ],
            [
                'id_plan' => 1,
                'fecha' => '2026-02-17',
                'nombre' => 'VO2Max clásico',
                'descripcion' => 'Intervalos de 3-5 minutos intensos',
                'completada' => 0,
            ],
            [
                'id_plan' => 1,
                'fecha' => '2026-02-19',
                'nombre' => 'Tirada larga',
                'descripcion' => 'Sesión de fondo para mejorar resistencia',
                'completada' => 1,
            ],

            // PLAN 2
            [
                'id_plan' => 2,
                'fecha' => '2026-01-20',
                'nombre' => 'Sweet Spot progresivo',
                'descripcion' => 'Trabajo de umbral',
                'completada' => 0,
            ],
            [
                'id_plan' => 2,
                'fecha' => '2026-01-22',
                'nombre' => 'Umbral sostenido',
                'descripcion' => 'Intervalos largos al 95% FTP',
                'completada' => 1,
            ],
            [
                'id_plan' => 2,
                'fecha' => '2026-01-25',
                'nombre' => 'Fuerza en subida',
                'descripcion' => 'Trabajo de baja cadencia',
                'completada' => 1,
            ],
            [
                'id_plan' => 2,
                'fecha' => '2026-01-27',
                'nombre' => 'Recuperación activa',
                'descripcion' => 'Rodaje suave para soltar piernas',
                'completada' => 1,
            ],
            [
                'id_plan' => 2,
                'fecha' => '2026-01-30',
                'nombre' => 'Test de potencia',
                'descripcion' => 'Evaluación de rendimiento',
                'completada' => 0,
            ],
        ]);
    }
}
