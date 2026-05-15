<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {

        Schema::create('pedidos_compra_linea', function (Blueprint $table) {

            $table->id();

            $table->foreignId('pedido_compra_id')->constrained('pedidos_compra')->onDelete('cascade');

            $table->foreignId('producto_id')->constrained('productos')->onDelete('restrict');

            $table->integer('cantidad');

            $table->decimal('precio_unitario', 10, 2)->comment('precio_compra_final del producto (con IVA)');

            $table->decimal('total', 10, 2)->comment('cantidad × precio_unitario');

            $table->timestamps();

            $table->index('pedido_compra_id');

            $table->index('producto_id');

        });

    }

    public function down(): void {
        Schema::dropIfExists('pedidos_compra_linea');
    }

};