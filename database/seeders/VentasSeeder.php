<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VentasSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📊 Creando ventas con clientes...');

        $admin = DB::table('users')->where('email', 'admin@admin.com')->first();
        $productos = DB::table('productos')->get();

        $productoRtx = $productos->where('nombre', 'RTX 4060 8GB')->first();
        $productoG203 = $productos->where('nombre', 'Logitech G203')->first();

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
                'subtotal' => 1400.00,
                'impuesto' => 294.00,
                'total' => 1694.00,
                'metodo_pago' => 'tarjeta',
                'estado' => 'completada',
                'observaciones' => 'Venta completada - 5x RTX 4060',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        $this->command->line('✅ Venta 1 creada: Juan García - 1694€');

        // ✅ CREAR O BUSCAR CLIENTE 2
        $cliente2 = $this->obtenerOCrearCliente(
            'María',
            'López',
            '87654321B',
            '666987654'
        );

        // ✅ VENTA 2 - PENDIENTE
        DB::table('ventas')->insert([
            [
                'numero_factura' => 'FAC-V-002',
                'cliente_id' => $cliente2->id,
                'cliente' => $cliente2->nombre . ' ' . $cliente2->apellido,
                'cliente_documento' => $cliente2->documento,
                'usuario_id' => $admin->id,
                'fecha_venta' => '2026-05-04 14:15:00',
                'subtotal' => 360.00,
                'impuesto' => 75.60,
                'total' => 435.60,
                'metodo_pago' => 'transferencia',
                'estado' => 'completada',
                'observaciones' => 'Venta completada - 20x Logitech G203',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        $this->command->line('✅ Venta 2 creada: María López - 435.60€');

        // ✅ CLIENTE GENÉRICO (para ventas sin cliente registrado)
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
                'subtotal' => 90.00,
                'impuesto' => 18.90,
                'total' => 108.90,
                'metodo_pago' => 'efectivo',
                'estado' => 'completada',
                'observaciones' => 'Venta mostrador',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        $this->command->line('✅ Venta 3 creada: Cliente Mostrador - 108.90€');

        $this->command->info('✅ Ventas creadas correctamente');
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