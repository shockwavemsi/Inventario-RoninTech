<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('albaranes_venta_linea', function (Blueprint $table) {
            $table->id();
            $table->foreignId('albaran_venta_id')->constrained('albaranes_venta')->onDelete('cascade');
            $table->foreignId('pedido_venta_linea_id')->constrained('pedidos_venta_linea');
            $table->foreignId('producto_id')->constrained('productos');
            $table->integer('cantidad_entregada');
            $table->integer('cantidad_rechazada')->default(0);
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('impuesto_porcentaje', 5, 2)->default(21.00);
            $table->decimal('impuesto_cantidad', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('albaranes_venta_linea');
    }
};