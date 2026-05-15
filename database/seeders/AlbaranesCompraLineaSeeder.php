<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlbaranesCompraLineaSeeder extends Seeder
{
    public function run(): void
    {
        $lineas = [
            // ALB-COMP-001: Completo
            [
                'albaran_compra_id' => 1,
                'producto_id' => 4,  // RTX 4060
                'cantidad_pedida' => 1,
                'cantidad_recibida' => 1,
                'cantidad_faltante' => 0,
                'estado' => 'recibido',
            ],
            [
                'albaran_compra_id' => 1,
                'producto_id' => 8,  // Samsung 980
                'cantidad_pedida' => 1,
                'cantidad_recibida' => 1,
                'cantidad_faltante' => 0,
                'estado' => 'recibido',
            ],
            [
                'albaran_compra_id' => 1,
                'producto_id' => 10,  // Corsair RAM
                'cantidad_pedida' => 1,
                'cantidad_recibida' => 1,
                'cantidad_faltante' => 0,
                'estado' => 'recibido',
            ],

            // ALB-COMP-002: Parcial (falta Ryzen 7700X)
            [
                'albaran_compra_id' => 2,
                'producto_id' => 1,  // Ryzen 5600G
                'cantidad_pedida' => 1,
                'cantidad_recibida' => 1,
                'cantidad_faltante' => 0,
                'estado' => 'recibido',
            ],
            [
                'albaran_compra_id' => 2,
                'producto_id' => 2,  // Ryzen 7700X
                'cantidad_pedida' => 1,
                'cantidad_recibida' => 0,
                'cantidad_faltante' => 1,
                'estado' => 'falta',
            ],

            // ALB-COMP-003: Completo
            [
                'albaran_compra_id' => 3,
                'producto_id' => 3,  // Core i5-12400F
                'cantidad_pedida' => 1,
                'cantidad_recibida' => 1,
                'cantidad_faltante' => 0,
                'estado' => 'recibido',
            ],
        ];

        DB::table('albaranes_compra_linea')->insert($lineas);
        $this->command->info('✅ Líneas de albaranes de compra creadas');
    }
}