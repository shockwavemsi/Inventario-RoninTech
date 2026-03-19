<?php
// database/migrations/2024_01_01_000006_create_ventas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->integer('cantidad');
            $table->string('cliente', 200)->nullable();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('restrict');
            $table->timestamps(); // created_at = fecha_venta
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
