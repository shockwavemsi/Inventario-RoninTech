<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('debitos_venta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_venta_id')->constrained('facturas_venta');
            $table->string('numero_debito')->unique();
            $table->date('fecha_debito');
            $table->enum('motivo', ['devolucion', 'ajuste', 'descuento', 'otro'])->default('otro');
            $table->decimal('total', 10, 2);
            $table->enum('estado', ['abierto', 'aplicado', 'cancelado'])->default('abierto');
            $table->text('observaciones')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('debitos_venta');
    }
};