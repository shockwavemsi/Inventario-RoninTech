<?php

namespace Database\Seeders;

use App\Models\FormaPago;
use Illuminate\Database\Seeder;

class FormaPagoSeeder extends Seeder
{
    public function run(): void
    {
        $formasPago = [
            [
                'nombre' => 'Transferencia Bancaria',
                'descripcion' => 'Pago mediante transferencia bancaria',
                'icono' => 'bank',
                'activo' => true
            ],
            [
                'nombre' => 'Cheque',
                'descripcion' => 'Pago con cheque bancario',
                'icono' => 'check',
                'activo' => true
            ],
            [
                'nombre' => 'Efectivo',
                'descripcion' => 'Pago en efectivo',
                'icono' => 'cash',
                'activo' => true
            ],
            [
                'nombre' => 'Tarjeta de Crédito',
                'descripcion' => 'Pago con tarjeta de crédito',
                'icono' => 'credit-card',
                'activo' => true
            ],
            [
                'nombre' => 'Letra de Cambio',
                'descripcion' => 'Pago mediante letra de cambio',
                'icono' => 'document',
                'activo' => true
            ],
        ];

        foreach ($formasPago as $forma) {
            FormaPago::firstOrCreate(['nombre' => $forma['nombre']], $forma);
        }

        echo "✅ Formas de pago creadas/actualizadas\n";
    }
}