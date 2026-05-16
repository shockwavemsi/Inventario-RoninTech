<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('pagos_factura', function (Blueprint $table) {
        // Eliminar la FK antigua
        if (Schema::hasColumn('pagos_factura', 'metodo_pago_id')) {
            // Primero eliminar datos
            DB::table('pagos_factura')->truncate();

            // Luego eliminar la FK y la columna
            $table->dropForeign(['metodo_pago_id']);
            $table->dropColumn('metodo_pago_id');
        }

        // Agregar columna nueva
        $table->foreignId('forma_pago_proveedor_id')
            ->nullable()
            ->constrained('formas_pago_proveedor')
            ->nullOnDelete();
    });
}

    public function down(): void
    {
        Schema::table('pagos_factura', function (Blueprint $table) {
            // Revertir cambios
            if (Schema::hasColumn('pagos_factura', 'forma_pago_proveedor_id')) {
                $table->dropForeign(['forma_pago_proveedor_id']);
                $table->dropColumn('forma_pago_proveedor_id');
            }

            $table->foreignId('metodo_pago_id')
                ->nullable()
                ->constrained('metodos_pago')
                ->nullOnDelete();
        });
    }
};