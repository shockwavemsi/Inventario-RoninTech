<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bancos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // BBVA, CaixaBank, Santander, etc
            $table->string('codigo_banco')->nullable(); // 0182, 0049, etc
            $table->string('pais', 2)->default('ES'); // País ISO
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bancos');
    }
};