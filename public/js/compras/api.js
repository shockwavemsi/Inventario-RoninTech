/**
 * API - Llamadas AJAX al servidor
 */

class ComprasAPI {
    constructor() {
        this.baseUrl = '/api';
    }

    async fetchTab(tabId) {
    try {
        const endpoints = {
            'pedidos': `/api/pedidos`,        // ✅ ORIGINAL (FUNCIONA)
            'albaranes': `/api/albaranes`,    // ✅ ORIGINAL (FUNCIONA)
            'facturas': `/api/facturas`       // ✅ ORIGINAL (FUNCIONA)
        };

        const url = endpoints[tabId];
        if (!url) throw new Error(`Tab desconocido: ${tabId}`);

        const response = await fetch(url);
        if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);

        return await response.json();
    } catch (error) {
        console.error('Error en fetchTab:', error);
        return null;
    }
}

    async fetchDetalles(id, tipo) {
        try {
            const response = await fetch(`/compras/tipos/{id}/json`);
            if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);
            return await response.json();
        } catch (error) {
            console.error('Error en fetchDetalles:', error);
            return null;
        }
    }
}
export default ComprasAPI;