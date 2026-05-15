<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FacturaCompra;

class FacturasCompraLineaSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📋 Creando líneas de facturas...');

        $facturas = FacturaCompra::with('albaranCompra.lineas.producto')->get();

        if ($facturas->isEmpty()) {
            $this->command->warn('⚠️ No hay facturas.');
            return;
        }

        foreach ($facturas as $factura) {
            // Obtener líneas del ALBARÁN
            foreach ($factura->albaranCompra->lineas as $lineaAlbaran) {
                $cantidad = (int) ($lineaAlbaran->cantidad_recibida ?? 0);

                // Solo si se recibió algo
                if ($cantidad > 0) {
                    $producto = $lineaAlbaran->producto;

                    // ✅ USAR DATOS DEL PRODUCTO DIRECTAMENTE
                    $precio_base = (float) $producto->precio_base_compra;
                    $iva_porcentaje = (float) $producto->porcentaje_iva_compra;
                    $precio_final = (float) $producto->precio_compra_final;

                    // Crear línea
                    $factura->lineas()->create([
                        'producto_id' => $lineaAlbaran->producto_id,
                        'cantidad' => $cantidad,
                        'precio_base_compra' => $precio_base,
                        'porcentaje_iva_compra' => $iva_porcentaje,
                        'precio_compra_final' => $precio_final,
                    ]);

                    $this->command->line(
                        "✅ {$producto->nombre} x{$cantidad} = {$precio_final}€"
                    );
                }
            }

            // ✅ NO ACTUALIZAR TOTAL - Ya está asignado en FacturasCompraSeeder
            $this->command->info("💾 FAC-COMP-{$factura->id}\n");
        }

        $this->command->info('✅ Líneas creadas correctamente');
    }
}