<?php

namespace Database\Seeders;

use App\Models\Banco;
use App\Models\FormaPago;
use App\Models\Proveedor;
use App\Models\FormasPagoProveedor;
use Illuminate\Database\Seeder;

class FormasPagoProveedorSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener datos
        $transferencia = FormaPago::where('nombre', 'Transferencia Bancaria')->first();
        $cheque = FormaPago::where('nombre', 'Cheque')->first();
        $efectivo = FormaPago::where('nombre', 'Efectivo')->first();
        $tarjeta = FormaPago::where('nombre', 'Tarjeta de Crédito')->first();

        $bbva = Banco::where('nombre', 'BBVA')->first();
        $santander = Banco::where('nombre', 'Santander')->first();
        $caixa = Banco::where('nombre', 'CaixaBank')->first();

        // Obtener proveedores
        $proveedores = Proveedor::all();

        foreach ($proveedores as $proveedor) {
            // Transferencia BBVA
            FormasPagoProveedor::firstOrCreate([
                'proveedor_id' => $proveedor->id,
                'forma_pago_id' => $transferencia->id,
                'banco_id' => $bbva->id,
            ], [
                'referencia' => 'ES91 1234 5678 9012 3456 7890 12',
                'nombre_banco' => 'Cuenta corriente principal'
            ]);

            // Transferencia Santander (segunda cuenta)
            FormasPagoProveedor::firstOrCreate([
                'proveedor_id' => $proveedor->id,
                'forma_pago_id' => $transferencia->id,
                'banco_id' => $santander->id,
            ], [
                'referencia' => 'ES93 0049 1234 5678 9012 3456 90',
                'nombre_banco' => 'Cuenta secundaria'
            ]);

            // Cheque
            FormasPagoProveedor::firstOrCreate([
                'proveedor_id' => $proveedor->id,
                'forma_pago_id' => $cheque->id,
                'banco_id' => null,
            ], [
                'referencia' => null,
                'nombre_banco' => 'Cheques corporativos'
            ]);

            // Efectivo
            FormasPagoProveedor::firstOrCreate([
                'proveedor_id' => $proveedor->id,
                'forma_pago_id' => $efectivo->id,
                'banco_id' => null,
            ], [
                'referencia' => null,
                'nombre_banco' => 'Pago en mano'
            ]);
        }

        echo "✅ Formas de pago por proveedor creadas/actualizadas\n";
    }
}