<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCiclistasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciclista', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellidos');
            $table->date('fecha_nacimiento');
            $table->decimal('peso_base', 5, 2);
            $table->integer('altura_base');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        }); // crea ya la tabla ciclista en MYSQL.
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ciclistas');
    }
}
