<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {

        Schema::create('pedidos_compra', function (Blueprint $table) {

            $table->id();

            $table->string('numero_pedido', 50)->unique();

            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');

            $table->foreignId('usuario_id')->nullable()->constrained('users')->onDelete('set null');

            $table->date('fecha_pedido');

            $table->date('fecha_entrega_esperada')->nullable();

            $table->enum('estado', ['abierto', 'parcial', 'completo', 'cancelado'])->default('abierto');

            $table->decimal('subtotal', 10, 2)->default(0);

            $table->decimal('descuento_porcentaje', 5, 2)->default(0);

            $table->decimal('descuento_cantidad', 10, 2)->default(0);

            $table->decimal('total', 10, 2)->default(0);  // SOLO: subtotal - descuento

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index('numero_pedido');

            $table->index('proveedor_id');

            $table->index('estado');

            $table->index('fecha_pedido');

        });

    }

    public function down(): void {
        Schema::dropIfExists('pedidos_compra');
    }

};