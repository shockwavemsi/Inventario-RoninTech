/**
 * API Manager - Gestión de llamadas API
 */

import { ENDPOINTS } from './config.js';

export class ApiManager {
    static async getVenta(ventaId) {
        const response = await fetch(`/ventas/${ventaId}/json`);
        if (!response.ok) throw new Error('Error al obtener venta');
        return response.json();
    }

    static async crearVenta(datos) {
        const response = await fetch(ENDPOINTS.STORE, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(datos)
        });
        return response.json();
    }

    static async buscarProductos(query) {
        const response = await fetch(`${ENDPOINTS.BUSCAR_PRODUCTOS}?q=${query}`);
        return response.json();
    }
}

export const getVenta = ApiManager.getVenta;
export const crearVenta = ApiManager.crearVenta;
export const buscarProductos = ApiManager.buscarProductos;

export default ApiManager;
