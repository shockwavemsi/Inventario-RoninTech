<?php
// database/migrations/2025_01_01_000008_create_movimientos_stock_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');  // ← AQUÍ
            $table->enum('tipo', ['entrada_compra', 'salida_venta', 'devolucion_venta', 'ajuste', 'inventario_inicial']);
            $table->integer('cantidad');
            $table->integer('stock_anterior')->nullable();
            $table->integer('stock_nuevo')->nullable();
            $table->string('referencia_tipo', 50)->nullable();
            $table->integer('referencia_id')->nullable();
            $table->text('motivo')->nullable();
            $table->foreignId('usuario_id')->constrained('users');
            $table->timestamps();
            $table->index(['referencia_tipo', 'referencia_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_stock');
    }
};