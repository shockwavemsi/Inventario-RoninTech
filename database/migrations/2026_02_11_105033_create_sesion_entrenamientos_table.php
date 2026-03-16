<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('sesion_entrenamientos', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('id_plan');
        $table->date('fecha');
        $table->string('nombre');
        $table->text('descripcion')->nullable();
        $table->boolean('completada')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesion_entrenamientos');
    }
};