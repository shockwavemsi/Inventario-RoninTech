/**
 * Config - Configuración central del módulo
 */

export const TABS = [
    { id: 'pedidos', label: 'Pedidos Compra', icon: 'file-earmark-text' },
    { id: 'albaranes', label: 'Albaranes', icon: 'boxes' },
    { id: 'facturas', label: 'Facturas', icon: 'receipt' }
];

export const BADGE_COLORS = {
    'completo': 'bg-success',
    'parcial': 'bg-warning',
    'abierto': 'bg-info',
    'recibido': 'bg-success',
    'falta': 'bg-danger',
    'cancelado': 'bg-danger',
    'pagada': 'bg-success',
    'pendiente': 'bg-warning'
};

export const COLORS = {
    primary: '#e63946',
    secondary: '#a0a0a0',
    success: '#90ee90',
    warning: '#fed7aa',
    danger: '#e63946',
    background: '#0d0d0e',
    surface: 'rgba(20, 20, 25, 0.65)'
};

export const UI_CONFIG = {
    animationDelay: 50,
    modalZIndex: 1050,
    cardsPerRow: 3
};