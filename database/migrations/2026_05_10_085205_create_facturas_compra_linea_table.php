<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('facturas_compra_linea', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('factura_compra_id');
            $table->unsignedBigInteger('producto_id');

            $table->integer('cantidad');
            $table->decimal('precio_base_compra', 10, 2)->nullable();
            $table->decimal('porcentaje_iva_compra', 5, 2)->default(21.00);
            $table->decimal('precio_compra_final', 10, 2)->nullable();

            $table->timestamps();

            $table->foreign('factura_compra_id')
                ->references('id')
                ->on('facturas_compra')
                ->onDelete('cascade');

            $table->foreign('producto_id')
                ->references('id')
                ->on('productos')
                ->onDelete('restrict');

            $table->index('factura_compra_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('facturas_compra_linea');
    }
};