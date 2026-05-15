<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('albaranes_compra_linea', function (Blueprint $table) {
            $table->id();

            $table->foreignId('albaran_compra_id')->constrained('albaranes_compra')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('restrict');

            // ✅ NUEVOS CAMPOS
            $table->integer('cantidad_pedida')->comment('Cantidad solicitada en el pedido');
            $table->integer('cantidad_recibida')->comment('Cantidad que llegó en el albarán');
            $table->integer('cantidad_faltante')->default(0)->comment('Cantidad que falta (diferencia)');

            // ✅ ESTADO DE LA LÍNEA
            $table->enum('estado', ['pendiente', 'recibido', 'falta', 'cancelado'])->default('pendiente');

            $table->timestamps();

            // ÍNDICES
            $table->index('albaran_compra_id');
            $table->index('producto_id');
            $table->index('estado');
        });
    }

    public function down(): void {
        Schema::dropIfExists('albaranes_compra_linea');
    }
};