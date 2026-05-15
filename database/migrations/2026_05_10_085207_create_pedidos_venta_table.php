<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pedidos_venta', function (Blueprint $table) {
            $table->id();
            $table->string('numero_pedido')->unique();
            $table->string('cliente_nombre');
            $table->string('cliente_documento')->nullable();
            $table->string('cliente_email')->nullable();
            $table->date('fecha_pedido');
            $table->date('fecha_entrega_esperada')->nullable();
            $table->enum('estado', ['abierto', 'confirmado', 'entregado_parcial', 'entregado'])->default('abierto');
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia', 'cheque'])->default('efectivo');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('impuesto', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pedidos_venta');
    }
};