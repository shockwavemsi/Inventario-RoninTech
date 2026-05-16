<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tabla_ivas', function (Blueprint $table) {
            $table->id();
            $table->decimal('porcentaje', 5, 2)->comment('Porcentaje de IVA: 21, 10, 4, etc');
            $table->string('descripcion', 100)->nullable()->comment('Ej: IVA 21%, IVA 10%, etc');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabla_ivas');
    }
};