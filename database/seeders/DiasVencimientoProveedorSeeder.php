<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use App\Models\DiasVencimientoProveedor;
use Illuminate\Database\Seeder;

class DiasVencimientoProveedorSeeder extends Seeder
{
    public function run(): void
    {
        $diasPorProveedor = [
            'DISTEC S.L.' => 15,
            'PC Componentes' => 20,
            'Logitech Iberia' => 30,
            'AMD Direct' => 25,
            'Intel Spain' => 30,
        ];

        foreach ($diasPorProveedor as $nombreProveedor => $dias) {
            $proveedor = Proveedor::where('nombre', $nombreProveedor)->first();

            if ($proveedor) {
                DiasVencimientoProveedor::firstOrCreate(
                    ['proveedor_id' => $proveedor->id],
                    ['dias_vencimiento' => $dias]
                );
            }
        }

        echo "✅ Días de vencimiento por proveedor creados/actualizados\n";
    }
}