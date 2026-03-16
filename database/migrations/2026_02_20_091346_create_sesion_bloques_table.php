<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesion_bloque', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('id_sesion_entrenamiento');
    $table->unsignedBigInteger('id_bloque_entrenamiento');
    $table->integer('orden');
    $table->integer('repeticiones')->default(1);

    $table->foreign('id_sesion_entrenamiento')
        ->references('id')
        ->on('sesion_entrenamientos')   // ← CORREGIDO
        ->onDelete('cascade')
        ->onUpdate('cascade');

    $table->foreign('id_bloque_entrenamiento')
        ->references('id')
        ->on('bloque_entrenamiento')    // ← esta sí existe en singular
        ->onDelete('cascade')
        ->onUpdate('cascade');
});

    }

    public function down(): void
    {
        Schema::dropIfExists('sesion_bloque');
    }
};
