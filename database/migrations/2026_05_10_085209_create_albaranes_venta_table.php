<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('albaranes_venta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_venta_id')->constrained('pedidos_venta');
            $table->string('numero_albaran')->unique();
            $table->date('fecha_albaran');
            $table->enum('estado', ['pendiente', 'entregado', 'devuelto'])->default('pendiente');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('impuesto', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('albaranes_venta');
    }
};