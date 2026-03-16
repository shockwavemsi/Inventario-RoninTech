<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(CiclistaSeeder::class);
        $this->call(BloqueEntrenamientoSeeder::class);
        $this->call(SesionEntrenamientoSeeder::class);
        $this->call(PlanEntrenamientoSeeder::class); 
        $this->call(HistoricoCiclistaSeeder::class);
        $this->call(SesionBloqueSeeder::class);
        $this->call(TipoComponenteSeeder::class);
        $this->call(BicicletaSeeder::class);
        $this->call(ComponenteBicicletaSeeder::class);
        // falta Entrenamiento seeder


    }
}
