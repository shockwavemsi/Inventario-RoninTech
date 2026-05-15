<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('facturas_compra', function (Blueprint $table) {
            $table->id();
            $table->string('numero_factura', 50)->unique();
            $table->foreignId('albaran_compra_id')->constrained('albaranes_compra')->onDelete('restrict');
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');
            $table->foreignId('usuario_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('fecha_factura');
            $table->date('fecha_vencimiento');
            $table->enum('estado', ['abierta', 'pagada', 'vencida'])->default('abierta');
            $table->decimal('total', 10, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('numero_factura');
            $table->index('albaran_compra_id');
            $table->index('estado');
        });
    }

    public function down(): void {
        Schema::dropIfExists('facturas_compra');
    }
};