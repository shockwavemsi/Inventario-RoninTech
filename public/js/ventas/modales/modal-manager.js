/**
 * Modal Manager - Ventas
 * Orquestador de todos los modales
 */
class ModalManager {
    static ventaModal = null;
    static facturaVentaModal = null;

    static init() {
        this.ventaModal = document.getElementById('modalVenta')
            ? new bootstrap.Modal(document.getElementById('modalVenta'))
            : null;

        this.facturaVentaModal = document.getElementById('modalFacturaVenta')
            ? new bootstrap.Modal(document.getElementById('modalFacturaVenta'))
            : null;

        console.log('✅ ModalManager inicializado');
    }

    static mostrarVenta() {
        if (this.ventaModal) this.ventaModal.show();
    }

    static ocultarVenta() {
        if (this.ventaModal) this.ventaModal.hide();
    }

    static mostrarFactura() {
        if (this.facturaVentaModal) this.facturaVentaModal.show();
    }

    static ocultarFactura() {
        if (this.facturaVentaModal) this.facturaVentaModal.hide();
    }
}

export default ModalManager;