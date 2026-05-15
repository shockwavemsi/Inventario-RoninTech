<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos_factura', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factura_compra_id');
            $table->unsignedBigInteger('metodo_pago_id');
            $table->decimal('monto', 10, 2);
            $table->date('fecha_pago')->nullable();
            $table->string('referencia', 100)->nullable();
            $table->json('detalles')->nullable();
            $table->enum('estado', ['pagado', 'pendiente', 'en_transito'])->default('pendiente');
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign Keys
            $table->foreign('factura_compra_id')
                ->references('id')
                ->on('facturas_compra')
                ->onDelete('cascade');

            $table->foreign('metodo_pago_id')
                ->references('id')
                ->on('metodos_pago')
                ->onDelete('restrict');

            $table->foreign('usuario_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            // Indexes
            $table->index('factura_compra_id');
            $table->index('metodo_pago_id');
            $table->index('estado');
            $table->index('fecha_pago');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos_factura');
    }
};