<?php
// database/seeders/AlertasSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlertasSeeder extends Seeder
{
    public function run()
    {
        // Verificar stocks actuales (esto se haría mejor con lógica en la app)
        DB::table('alertas')->insert([
            [
                'producto_id' => 2, // RTX
                'mensaje' => 'Stock bajo de RTX 4060 (quedan 2 unidades)',
                'vista' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'producto_id' => 5, // Corsair
                'mensaje' => 'Stock bajo de Corsair Vengeance (quedan 4 unidades)',
                'vista' => true,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2)
            ],
        ]);
    }
}
