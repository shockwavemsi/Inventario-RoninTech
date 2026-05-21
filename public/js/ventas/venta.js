/**
 * Venta Manager
 */

import UIManager from './ui.js';
import ApiManager from './api.js';
import ModalManager from './modales/modal-manager.js';
import CrearVentaModal from './modales/crear-venta-modal.js';
import CrearFacturaVentaModal from './modales/crear-factura-venta-modal.js';

export class VentaManager {
    static init() {
        console.log('✅ VentaManager inicializado');
        UIManager.init();
        ModalManager.init();
        CrearVentaModal.init();
        CrearFacturaVentaModal.init();
    }

    static async cargarVenta(ventaId) {
        try {
            const venta = await ApiManager.getVenta(ventaId);
            return venta;
        } catch (error) {
            console.error('Error:', error);
            return null;
        }
    }
}

window.VentaManager = VentaManager;
window.CrearVentaModal = CrearVentaModal;
window.CrearFacturaVentaModal = CrearFacturaVentaModal;

document.addEventListener('DOMContentLoaded', () => {
    VentaManager.init();
});

export default VentaManager;
