<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pedidos_venta_linea', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_venta_id')->constrained('pedidos_venta')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('impuesto_porcentaje', 5, 2)->default(21.00);
            $table->decimal('impuesto_cantidad', 10, 2)->default(0);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pedidos_venta_linea');
    }
};