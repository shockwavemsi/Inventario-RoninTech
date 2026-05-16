<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dias_vencimiento_proveedores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')
                ->unique()
                ->constrained('proveedores')
                ->cascadeOnDelete();
            $table->integer('dias_vencimiento')->default(30); // Días de validez de factura
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dias_vencimiento_proveedores');
    }
};