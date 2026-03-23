<?php
// database/migrations/2025_01_01_000007_create_ventas_detalle_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
            
            $table->index(['venta_id', 'producto_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas_detalle');
    }
};