<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('debitos_compra', function (Blueprint $table) {
            $table->id();

            $table->string('numero_debito', 50)->unique()->comment('DBT-001, DBT-002...');
            $table->foreignId('albaran_compra_id')->constrained('albaranes_compra')->onDelete('cascade');
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');

            $table->date('fecha_debito')->comment('Cuándo se generó el débito');
            $table->date('fecha_vencimiento')->nullable()->comment('Fecha esperada de resolución');

            // ✅ ESTADO DEL DÉBITO
            $table->enum('estado', ['abierto', 'pagado', 'cancelado'])->default('abierto')
                ->comment('abierto: pendiente | pagado: resuelto | cancelado: anulado');

            $table->text('observaciones')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // ÍNDICES
            $table->index('numero_debito');
            $table->index('albaran_compra_id');
            $table->index('proveedor_id');
            $table->index('estado');
        });
    }

    public function down(): void {
        Schema::dropIfExists('debitos_compra');
    }
};