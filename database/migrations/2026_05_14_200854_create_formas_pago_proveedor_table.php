<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formas_pago_proveedor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')
                ->constrained('proveedores')
                ->cascadeOnDelete();
            $table->foreignId('forma_pago_id')
                ->constrained('formas_pago')
                ->cascadeOnDelete();
            $table->foreignId('banco_id')
                ->nullable()
                ->constrained('bancos')
                ->nullOnDelete();
             $table->string('referencia')->nullable(); // ES91 1234 5678..., CHQ-001, etc
            $table->string('nombre_banco')->nullable(); // "Cuenta corriente principal", "Cheques corporativos"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formas_pago_proveedor');
    }
};