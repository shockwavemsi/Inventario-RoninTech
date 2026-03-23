<?php
// database/migrations/2025_01_01_000005_create_compras_detalle_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compras_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compra_id')->constrained('compras')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
            
            // Índices para mejorar rendimiento
            $table->index(['compra_id', 'producto_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compras_detalle');
    }
};