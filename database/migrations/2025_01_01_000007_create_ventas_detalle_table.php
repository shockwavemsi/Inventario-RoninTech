<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints(); // ✅ DESHABILITAR TEMPORALMENTE

        Schema::create('ventas_detalle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venta_id'); // ✅ Tipo explícito
            $table->unsignedBigInteger('producto_id'); // ✅ Tipo explícito
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();

            // ✅ AGREGAR CONSTRAINTS DESPUÉS
            $table->foreign('venta_id')->references('id')->on('ventas')->onDelete('cascade');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
        });

        Schema::enableForeignKeyConstraints(); // ✅ REABILITAR
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('ventas_detalle');
        Schema::enableForeignKeyConstraints();
    }
};