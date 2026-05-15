/**
 * Modal - Orquestador central de modales
 */

import PedidosModal from './modales/pedidos-modal.js';
import AlbaranesModal from './modales/albaranes-modal.js';
import FacturasModal from './modales/facturas-modal.js';

class ComprasModal {

    static async mostrar(id, tipo) {

        try {

            // Mapeo especial para "albaran" → "albaranes"
            const tipoUrl = tipo === 'albaran' ? 'albaranes' : tipo + 's';
            const response = await fetch(`/compras/${tipoUrl}/${id}/json`);

            if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);

            const item = await response.json();

            if (item.error) throw new Error(item.error);

            // ✅ Delegar al módulo correspondiente - ESTO INCLUYE cargarRelaciones
            if (tipo === 'pedido') {
                PedidosModal.mostrar(item);
            } else if (tipo === 'albaran') {
                AlbaranesModal.mostrar(item);
            } else if (tipo === 'factura') {
                FacturasModal.mostrar(item);
            }

        } catch (error) {
            alert('❌ Error: ' + error.message);
        }

    }

    static cerrarModal() {
        const modal = document.getElementById('detalleModal');
        if (modal) {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) bsModal.hide();
        }
    }

}

export default ComprasModal;