<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Vista 1: Albaranes para factura
        DB::statement("
            CREATE OR REPLACE VIEW vw_albaranes_factura AS
            SELECT
                ac.id,
                ac.numero_albaran,
                ac.proveedor_id,
                prov.nombre as proveedor,
                pc.numero_pedido,
                ac.total,
                ac.estado,
                ac.fecha_albaran,
                COUNT(acl.id) as total_lineas,
                SUM(CASE WHEN acl.cantidad_faltante > 0 THEN 1 ELSE 0 END) as lineas_faltantes
            FROM albaranes_compra ac
            JOIN proveedores prov ON prov.id = ac.proveedor_id
            LEFT JOIN pedidos_compra pc ON pc.id = ac.pedido_compra_id
            LEFT JOIN albaranes_compra_linea acl ON acl.albaran_compra_id = ac.id
            WHERE ac.estado IN ('recibido', 'parcial')
            GROUP BY ac.id
            ORDER BY ac.id DESC
        ");

        // Vista 2: Productos del albarán
        DB::statement("
            CREATE OR REPLACE VIEW vw_albaranes_productos AS
            SELECT
                ac.id as albaran_id,
                ac.numero_albaran,
                ac.proveedor_id,
                acl.id as linea_id,
                p.id as producto_id,
                p.nombre as producto,
                p.marca,
                p.modelo,
                acl.cantidad_pedida,
                acl.cantidad_recibida,
                acl.cantidad_faltante,
                acl.estado,
                p.precio_compra_final,
                p.porcentaje_iva_compra,
                (acl.cantidad_recibida * p.precio_compra_final * (1 + p.porcentaje_iva_compra / 100)) as subtotal
            FROM albaranes_compra ac
            JOIN albaranes_compra_linea acl ON acl.albaran_compra_id = ac.id
            JOIN productos p ON p.id = acl.producto_id
            ORDER BY ac.id, acl.id
        ");

        // Vista 3: Formas de pago disponibles
        DB::statement("
            CREATE OR REPLACE VIEW vw_formas_pago_disponibles AS
            SELECT
                fpp.id as relacion_id,
                fpp.proveedor_id,
                fpp.forma_pago_id,
                fp.nombre as forma_pago,
                fpp.banco_id,
                b.nombre as banco,
                fpp.referencia,
                fpp.nombre_banco,
                CONCAT(fp.nombre, IF(b.nombre IS NOT NULL, CONCAT(' (', b.nombre, ')'), ''), 
                       IF(fpp.nombre_banco IS NOT NULL, CONCAT(' - ', fpp.nombre_banco), '')) as label_completo
            FROM formas_pago_proveedor fpp
            JOIN formas_pago fp ON fp.id = fpp.forma_pago_id
            LEFT JOIN bancos b ON b.id = fpp.banco_id
            ORDER BY fpp.proveedor_id, fp.nombre
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_albaranes_factura");
        DB::statement("DROP VIEW IF EXISTS vw_albaranes_productos");
        DB::statement("DROP VIEW IF EXISTS vw_formas_pago_disponibles");
    }
};