<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VentasSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📊 Creando cabeceras de ventas...');

        $admin = DB::table('users')->where('email', 'admin@admin.com')->first();

        // ✅ CREAR O BUSCAR CLIENTE 1
        $cliente1 = $this->obtenerOCrearCliente(
            'Juan',
            'García',
            '12345678A',
            '666123456'
        );

        // ✅ VENTA 1 - COMPLETADA
        DB::table('ventas')->insert([
            [
                'numero_factura' => 'FAC-V-001',
                'cliente_id' => $cliente1->id,
                'cliente' => $cliente1->nombre . ' ' . $cliente1->apellido,
                'cliente_documento' => $cliente1->documento,
                'usuario_id' => $admin->id,
                'fecha_venta' => '2026-05-01 10:30:00',
                'subtotal' => 0, // Se calcula en detalles
                'impuesto' => 0,
                'total' => 0,
                'metodo_pago' => 'tarjeta',
                'estado' => 'completada',
                'observaciones' => 'Venta completada - 3 productos',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        $this->command->line('✅ Venta 1 creada: Juan García');

        // ✅ CREAR O BUSCAR CLIENTE 2
        $cliente2 = $this->obtenerOCrearCliente(
            'María',
            'López',
            '87654321B',
            '666987654'
        );

        // ✅ VENTA 2 - COMPLETADA
        DB::table('ventas')->insert([
            [
                'numero_factura' => 'FAC-V-002',
                'cliente_id' => $cliente2->id,
                'cliente' => $cliente2->nombre . ' ' . $cliente2->apellido,
                'cliente_documento' => $cliente2->documento,
                'usuario_id' => $admin->id,
                'fecha_venta' => '2026-05-04 14:15:00',
                'subtotal' => 0,
                'impuesto' => 0,
                'total' => 0,
                'metodo_pago' => 'transferencia',
                'estado' => 'completada',
                'observaciones' => 'Venta completada - 2 productos',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        $this->command->line('✅ Venta 2 creada: María López');

        // ✅ CLIENTE GENÉRICO
        $clienteGenerico = $this->obtenerOCrearCliente(
            'Cliente',
            'Genérico',
            '00000000X',
            '000000000'
        );

        // ✅ VENTA 3 - CON CLIENTE GENÉRICO
        DB::table('ventas')->insert([
            [
                'numero_factura' => 'FAC-V-003',
                'cliente_id' => $clienteGenerico->id,
                'cliente' => 'Cliente Mostrador',
                'cliente_documento' => null,
                'usuario_id' => $admin->id,
                'fecha_venta' => '2026-05-05 09:45:00',
                'subtotal' => 0,
                'impuesto' => 0,
                'total' => 0,
                'metodo_pago' => 'efectivo',
                'estado' => 'completada',
                'observaciones' => 'Venta mostrador - 4 productos',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        $this->command->line('✅ Venta 3 creada: Cliente Mostrador');

        $this->command->info('✅ Cabeceras de ventas creadas');
    }

    /**
     * Obtener cliente por documento o crear si no existe
     */
    private function obtenerOCrearCliente($nombre, $apellido, $documento, $telefono)
    {
        $cliente = DB::table('clientes')
            ->where('documento', $documento)
            ->first();

        if (!$cliente) {
            $id = DB::table('clientes')->insertGetId([
                'nombre' => $nombre,
                'apellido' => $apellido,
                'documento' => $documento,
                'telefono' => $telefono,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return DB::table('clientes')->find($id);
        }

        return $cliente;
    }
}