<?php
// database/migrations/2025_01_01_000001_create_proveedores_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 200);
            $table->string('ruc', 20)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('direccion')->nullable();
            $table->string('contacto_nombre', 100)->nullable();
            $table->string('contacto_telefono', 20)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};