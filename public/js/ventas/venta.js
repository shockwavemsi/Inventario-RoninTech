/**
 * Venta - Orquestador Principal
 * Inicializa todos los módulos de ventas
 */
import CrearVentaModal from './modales/crear-venta-modal.js';
import CrearFacturaVentaModal from './modales/crear-factura-venta-modal.js';
import ModalManager from './modales/modal-manager.js';

class VentaManager {
    static init() {
        console.log('🚀 Inicializando Venta Manager...');

        // Inicializar modales
        ModalManager.init();

        // Inicializar módulos
        CrearVentaModal.init();
        CrearFacturaVentaModal.init();

        // Exponer globalmente
        window.CrearVentaModal = CrearVentaModal;
        window.CrearFacturaVentaModal = CrearFacturaVentaModal;

        console.log('✅ Venta Manager ready!');
    }
}

// Auto-inicializar cuando DOM esté listo
document.addEventListener('DOMContentLoaded', () => VentaManager.init());

export default VentaManager;