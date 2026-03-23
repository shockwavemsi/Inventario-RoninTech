<?php
// database/migrations/2025_01_01_000012_create_alertas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos');
            $table->enum('tipo', ['stock_bajo', 'stock_exceso', 'vencimiento', 'producto_nuevo']);
            $table->text('mensaje');
            $table->boolean('vista')->default(false);
            $table->foreignId('usuario_visto')->nullable()->constrained('users');
            $table->datetime('fecha_visto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alertas');
    }
};