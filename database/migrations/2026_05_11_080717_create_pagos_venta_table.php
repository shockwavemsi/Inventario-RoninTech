<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos_venta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_venta_id')
                ->constrained('facturas_venta')
                ->onDelete('cascade');
            $table->foreignId('metodo_pago_id')
                ->constrained('metodos_pago')
                ->onDelete('restrict');
            $table->decimal('monto', 10, 2);
            $table->date('fecha_pago');
            $table->string('referencia')->nullable(); // Nº cheque, nº tarjeta, etc
            $table->json('detalles')->nullable(); // Datos específicos del pago
            $table->enum('estado', ['pendiente', 'pagado', 'cancelado', 'devuelto'])->default('pagado');
            $table->foreignId('usuario_id')
    ->constrained('users')
    ->onDelete('cascade')
    ->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->index('factura_venta_id');
            $table->index('metodo_pago_id');
            $table->index('fecha_pago');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos_venta');
    }
};