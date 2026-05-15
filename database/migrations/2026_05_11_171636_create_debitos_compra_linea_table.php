<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('debitos_compra_linea', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('debito_compra_id');
            $table->unsignedBigInteger('producto_id');

            $table->integer('cantidad')->comment('Cantidad pendiente');
            $table->enum('estado', ['pendiente', 'recibido', 'cancelado'])->default('pendiente');

            $table->timestamps();

            // Claves foráneas
            $table->foreign('debito_compra_id')
                ->references('id')
                ->on('debitos_compra')
                ->onDelete('cascade');

            $table->foreign('producto_id')
                ->references('id')
                ->on('productos')
                ->onDelete('restrict');

            // Índices
            $table->index('debito_compra_id');
            $table->index('producto_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('debitos_compra_linea');
    }
};