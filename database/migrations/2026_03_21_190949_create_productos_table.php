<?php
// database/migrations/2025_01_01_000003_create_productos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_barras', 100)->unique()->nullable();
            $table->string('sku', 50)->unique()->nullable();
            $table->string('nombre', 200);
            $table->text('descripcion')->nullable();
            $table->string('marca', 100)->nullable();
            $table->string('modelo', 100)->nullable();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->nullOnDelete();
            $table->decimal('precio_compra', 10, 2)->nullable();
            $table->decimal('precio_venta', 10, 2);
            $table->integer('stock_minimo')->default(3);
            $table->integer('stock_maximo')->default(100);
            $table->string('ubicacion', 100)->nullable();
            $table->string('imagen', 255)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};