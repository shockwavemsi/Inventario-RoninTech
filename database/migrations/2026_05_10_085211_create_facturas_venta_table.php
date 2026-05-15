<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('facturas_venta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('albaran_venta_id')->constrained('albaranes_venta');
            $table->string('numero_factura')->unique();
            $table->date('fecha_factura');
            $table->date('fecha_vencimiento')->nullable();
            $table->enum('estado', ['abierta', 'cobrada', 'cancelada'])->default('abierta');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('impuesto', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('facturas_venta');
    }
};