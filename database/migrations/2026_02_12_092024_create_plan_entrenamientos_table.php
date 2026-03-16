<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_entrenamiento', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_ciclista');
            $table->string('nombre', 100);
            $table->string('descripcion', 255)->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('objetivo', 100)->nullable();
            $table->boolean('activo')->default(1);

            // Clave foránea
            $table->foreign('id_ciclista')
                  ->references('id')
                  ->on('ciclista')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_entrenamiento');
    }
};