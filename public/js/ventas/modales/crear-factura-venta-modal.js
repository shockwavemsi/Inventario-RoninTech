/**
 * Crear Factura Venta Modal - Lógica del modal
 */
import ModalManager from './modal-manager.js';

class CrearFacturaVentaModal {
    static init() {
        console.log('✅ CrearFacturaVentaModal inicializado');
    }

    static mostrar() {
        ModalManager.mostrarFactura();
    }
}

export default CrearFacturaVentaModal;