<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historico_ciclista', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_ciclista');
            $table->date('fecha');

            $table->decimal('peso', 5, 2)->nullable();
            $table->integer('ftp')->nullable();
            $table->integer('pulso_max')->nullable();
            $table->integer('pulso_reposo')->nullable();
            $table->integer('potencia_max')->nullable();
            $table->decimal('grasa_corporal', 4, 2)->nullable();
            $table->decimal('vo2max', 4, 1)->nullable();

            $table->string('comentario', 255)->nullable();

            // Foreign key
            $table->foreign('id_ciclista')
                ->references('id')
                ->on('ciclista')
                ->onDelete('cascade')
                ->onUpdate('cascade'); // asi cuando se borra el ciclista se elimina tambien el historico coclista al que estaba ligado

            // Unique constraint
            $table->unique(['id_ciclista', 'fecha'], 'uq_ciclista_fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historico_ciclista');
    }
};
