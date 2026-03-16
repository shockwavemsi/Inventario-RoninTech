<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Ciclista;

class CiclistaSeeder extends Seeder
{
    public function run()
    {
        $ciclistas = [
            [
                'nombre' => 'Juan',
                'apellidos' => 'Pérez',
                'fecha_nacimiento' => '1990-05-10',
                'peso_base' => 70.5,
                'altura_base' => 175,
                'email' => 'test1@prueba.com',
                'password' => Hash::make('prueba'),
            ],
            [
                'nombre' => 'Ana',
                'apellidos' => 'Rodríguez',
                'fecha_nacimiento' => '1992-08-20',
                'peso_base' => 60.0,
                'altura_base' => 165,
                'email' => 'test2@prueba.com',
                'password' => Hash::make('prueba'),
            ],
            [
                'nombre' => 'Pedro',
                'apellidos' => 'García',
                'fecha_nacimiento' => '1995-03-15',
                'peso_base' => 80.0,
                'altura_base' => 180,
                'email' => 'test3@prueba.com',
                'password' => Hash::make('prueba'),
            ],
            [
                'nombre' => 'Carmen',
                'apellidos' => 'García',
                'fecha_nacimiento' => '1998-09-05',
                'peso_base' => 55.0,
                'altura_base' => 160,
                'email' => 'test4@prueba.com',
                'password' => Hash::make('prueba'),
            ],
            [
                'nombre' => 'Luis',
                'apellidos' => 'Rodríguez',
                'fecha_nacimiento' => '1972-09-15',
                'peso_base' => 62.0,
                'altura_base' => 170,
                'email' => 'test5@prueba.com',
                'password' => Hash::make('prueba'),
            ],
            [
                'nombre' => 'Maria',
                'apellidos' => 'Rodríguez',
                'fecha_nacimiento' => '1972-09-15',
                'peso_base' => 62.0,
                'altura_base' => 170,
                'email' => 'test6@prueba.com',
                'password' => Hash::make('prueba'),
            ],
            [
                'nombre' => 'Ricardo',
                'apellidos' => 'García',
                'fecha_nacimiento' => '1982-09-15',
                'peso_base' => 72.0,
                'altura_base' => 170,
                'email' => 'test7@prueba.com',
                'password' => Hash::make('prueba'),
            ],
        ];

        foreach ($ciclistas as $ciclista) {
            Ciclista::create($ciclista);
        }
    }
}