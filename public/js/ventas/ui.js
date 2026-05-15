/**
 * UI Manager - Ventas
 * Gestiona la interfaz y DOM
 */
import { IVA, ESTADOS_VENTA, COLORES } from './config.js';

export class UIManager {
    static formatearMoneda(valor) {
        return new Intl.NumberFormat('es-ES', {
            style: 'currency',
            currency: 'EUR',
        }).format(valor);
    }

    static getColorEstado(estado) {
        const est = ESTADOS_VENTA.find(e => e.value === estado);
        return est?.color || '#a0a0a0';
    }

    static mostrarAlerta(mensaje, tipo = 'info') {
        const alertClass = {
            'info': 'alert-info',
            'success': 'alert-success',
            'warning': 'alert-warning',
            'error': 'alert-danger',
        }[tipo];

        const alert = document.createElement('div');
        alert.className = `alert ${alertClass} alert-dismissible fade show`;
        alert.setAttribute('role', 'alert');
        alert.innerHTML = `
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        const container = document.querySelector('.container-fluid') || document.body;
        container.insertBefore(alert, container.firstChild);

        setTimeout(() => alert.remove(), 5000);
    }

    static ocultarAlerta() {
        document.querySelectorAll('.alert').forEach(a => a.remove());
    }

    static limpiarFormulario(formId) {
        const form = document.getElementById(formId);
        if (form) form.reset();
    }

    static actualizarTabla(selector, datos) {
        const tbody = document.querySelector(selector);
        if (!tbody) return;
        tbody.innerHTML = datos;
    }

    static calcularTotales(lineas) {
        let subtotal = 0;
        lineas.forEach(linea => {
            subtotal += (linea.cantidad * linea.precio_unitario);
        });

        const iva = subtotal * IVA;
        const total = subtotal + iva;

        return { subtotal, iva, total };
    }
}

export default UIManager;