<?php
// database/migrations/2025_01_01_000004_create_compras_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores');
            $table->string('numero_factura', 50)->nullable();
            $table->date('fecha_pedido');
            $table->date('fecha_entrega_esperada')->nullable();
            $table->date('fecha_entrega_real')->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->decimal('impuesto', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->enum('estado', ['pendiente', 'enviado', 'parcial', 'recibido', 'cancelado'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->foreignId('usuario_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};