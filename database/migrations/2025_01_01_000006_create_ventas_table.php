<?php
// database/migrations/2025_01_01_000006_create_ventas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_factura', 50)->unique()->nullable();
            $table->datetime('fecha_venta')->useCurrent();
            $table->string('cliente', 200)->nullable();
            $table->string('cliente_documento', 20)->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->decimal('impuesto', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia', 'credito'])->default('efectivo');
            $table->enum('estado', ['completada', 'cancelada', 'pendiente'])->default('completada');
            $table->foreignId('usuario_id')->constrained('users');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};