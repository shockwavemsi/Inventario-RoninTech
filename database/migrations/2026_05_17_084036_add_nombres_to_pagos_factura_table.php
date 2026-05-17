<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pagos_factura', function (Blueprint $table) {
            $table->string('banco_nombre', 100)->nullable()->after('forma_pago_proveedor_id');
            $table->string('forma_pago_nombre', 100)->nullable()->after('banco_nombre');
        });
    }

    public function down(): void {
        Schema::table('pagos_factura', function (Blueprint $table) {
            $table->dropColumn(['banco_nombre', 'forma_pago_nombre']);
        });
    }
};