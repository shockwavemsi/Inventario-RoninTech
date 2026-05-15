<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('albaranes_compra', function (Blueprint $table) {
            $table->id();

            $table->string('numero_albaran', 50)->unique();
            $table->foreignId('pedido_compra_id')->constrained('pedidos_compra')->onDelete('cascade');
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');

            $table->date('fecha_albaran');
            $table->date('fecha_recepcion')->nullable()->comment('Cuándo se recibió físicamente');

            // ✅ ESTADO DEL ALBARÁN COMPLETO
            $table->enum('estado', ['recibido', 'parcial', 'falta', 'cancelado'])->default('recibido')
    ->comment('recibido: todo OK | parcial: faltan cosas | falta: nada llegó | cancelado');

            $table->decimal('total', 10, 2)->default(0);
            $table->text('observaciones')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // ÍNDICES
            $table->index('numero_albaran');
            $table->index('pedido_compra_id');
            $table->index('proveedor_id');
            $table->index('estado');
            $table->index('fecha_albaran');
        });
    }

    public function down(): void {
        Schema::dropIfExists('albaranes_compra');
    }
};