/**
 * API - Ventas
 * Funciones para comunicarse con el backend
 */
import { ENDPOINTS } from './config.js';

export async function buscarProductos(query) {
    try {
        const response = await fetch(`${ENDPOINTS.BUSCAR_PRODUCTOS}?q=${encodeURIComponent(query)}`);
        if (!response.ok) throw new Error('Error en búsqueda');
        return await response.json();
    } catch (error) {
        console.error('❌ Error buscar productos:', error);
        return [];
    }
}

export async function crearVenta(datos) {
    try {
        const formData = new FormData();
        Object.keys(datos).forEach(key => {
            formData.append(key, datos[key]);
        });

        const response = await fetch(ENDPOINTS.STORE, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
        });

        if (!response.ok) throw new Error('Error al crear venta');
        return await response.json();
    } catch (error) {
        console.error('❌ Error crear venta:', error);
        throw error;
    }
}

export async function eliminarVenta(id) {
    try {
        const response = await fetch(ENDPOINTS.DESTROY(id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
        });

        if (!response.ok) throw new Error('Error al eliminar venta');
        return await response.json();
    } catch (error) {
        console.error('❌ Error eliminar venta:', error);
        throw error;
    }
}

export async function cambiarEstadoVenta(id, estado) {
    try {
        const response = await fetch(ENDPOINTS.ESTADO(id), {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({ estado }),
        });

        if (!response.ok) throw new Error('Error al cambiar estado');
        return await response.json();
    } catch (error) {
        console.error('❌ Error cambiar estado:', error);
        throw error;
    }
}