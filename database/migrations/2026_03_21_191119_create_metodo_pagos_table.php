<?php
// database/migrations/2025_01_01_000014_create_metodos_pago_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metodos_pago', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metodos_pago');
    }
};