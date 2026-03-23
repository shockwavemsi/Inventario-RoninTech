<?php
// database/migrations/2025_01_01_000013_create_configuracion_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_empresa', 200);
            $table->string('ruc', 20)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('direccion')->nullable();
            $table->string('logo', 255)->nullable();
            $table->decimal('impuesto_porcentaje', 5, 2)->default(21.00);
            $table->string('moneda', 10)->default('EUR');
            $table->string('formato_factura', 20)->default('FACT-{NRO}');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion');
    }
};