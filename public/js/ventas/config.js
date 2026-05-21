/**
 * Configuración - Ventas
 */

export const ENDPOINTS = {
    STORE: '/ventas',
    DESTROY: (id) => `/ventas/${id}`,
    BUSCAR_PRODUCTOS: '/ventas/buscar-productos',
    ESTADO: (id) => `/ventas/${id}/estado`,
};

export const IVA = 0.21; // 21%

export const BADGE_COLORS = {
    completada: '#90ee90',
    pendiente: '#ffc107',
    cancelada: '#dc3545',
};

export const TABS = {
    VENTAS: '#ventas-tab',
    FACTURAS: '#facturas-tab',
};

export const UI_CONFIG = {
    MODAL_VENTA: '#modalVenta',
    MODAL_VER_VENTA: '#modalVerVenta',
    TABLA_VENTAS: '#tablasVentas',
    TABLA_FACTURAS: '#tablasFacturas',
};

export const COLORES = {
    PRIMARIO: '#e63946',
    SECUNDARIO: '#f0f0f0',
    FONDO: 'rgba(20, 20, 25, 0.95)',
    BORDE: 'rgba(230, 57, 70, 0.3)',
    EXITO: '#90ee90',
    ADVERTENCIA: '#ffc107',
    PELIGRO: '#dc3545',
};

export const ESTADOS_VENTA = [
    { value: 'pendiente', label: 'Pendiente', color: '#ffc107' },
    { value: 'completada', label: 'Completada', color: '#90ee90' },
    { value: 'cancelada', label: 'Cancelada', color: '#dc3545' },
];

export default {
    ENDPOINTS,
    IVA,
    BADGE_COLORS,
    TABS,
    UI_CONFIG,
    COLORES,
    ESTADOS_VENTA,
};