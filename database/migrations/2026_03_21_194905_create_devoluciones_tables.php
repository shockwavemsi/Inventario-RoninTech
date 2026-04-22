<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // =============================================
        // 1. Tabla: devolucion_ventas
        // =============================================

        Schema::create('devolucion_ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
            $table->datetime('fecha')->useCurrent();
            $table->text('motivo');
            $table->decimal('total_devuelto', 10, 2);
            $table->enum('estado', ['pendiente', 'completada'])->default('pendiente'); // ✅ AGREGAR ESTO
            $table->foreignId('usuario_id')->constrained('users');
            $table->timestamps();
        });

        // =============================================
        // 2. Tabla: devoluciones_detalle
        // =============================================

        Schema::create('devoluciones_detalle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('devolucion_venta_id');
            $table->unsignedBigInteger('producto_id');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();

            // Índices
            $table->index('producto_id');
            $table->index('devolucion_venta_id');
        });

        // Añadir las claves foráneas
        Schema::table('devoluciones_detalle', function (Blueprint $table) {
            $table->foreign('devolucion_venta_id')
                  ->references('id')
                  ->on('devolucion_ventas')
                  ->onDelete('cascade');

            $table->foreign('producto_id')
                  ->references('id')
                  ->on('productos')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devoluciones_detalle');
        Schema::dropIfExists('devolucion_ventas');
    }
};