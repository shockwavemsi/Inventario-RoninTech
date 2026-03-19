<?php
// database/migrations/2024_01_01_000005_create_entradas_stock_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entradas_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->integer('cantidad');
            $table->string('proveedor', 200)->nullable();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('restrict');
            $table->text('observaciones')->nullable();
            $table->timestamps(); // created_at = fecha_entrada
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entradas_stock');
    }
};
