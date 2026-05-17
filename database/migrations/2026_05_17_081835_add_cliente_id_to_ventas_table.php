<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('ventas', function (Blueprint $table) {
            $table->foreignId('cliente_id')
                ->nullable()
                ->after('usuario_id')
                ->constrained('clientes')
                ->onDelete('restrict');
        });
    }

    public function down(): void {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['cliente_id']);
            $table->dropColumn('cliente_id');
        });
    }
};