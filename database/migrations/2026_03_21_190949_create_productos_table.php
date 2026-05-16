<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 200);
            $table->text('descripcion')->nullable();
            $table->string('marca', 100)->nullable();
            $table->string('modelo', 100)->nullable();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->nullOnDelete();

            // COMPRA
            $table->decimal('precio_base_compra', 10, 2)->nullable()->comment('Precio sin IVA');
            $table->foreignId('iva_compra_id')->nullable()->constrained('tabla_ivas')->nullOnDelete()->comment('IVA para compra');
            $table->decimal('precio_compra_final', 10, 2)->nullable()->comment('Precio con IVA');

            // VENTA
            $table->decimal('precio_base_venta', 10, 2)->comment('Precio sin IVA');
            $table->foreignId('iva_venta_id')->nullable()->constrained('tabla_ivas')->nullOnDelete()->comment('IVA para venta');
            $table->decimal('precio_venta_final', 10, 2)->nullable()->comment('Precio con IVA');

            // STOCK
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(3);
            $table->integer('stock_maximo')->default(100);

            // ADICIONALES
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