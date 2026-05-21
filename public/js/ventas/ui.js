/**
 * UI Manager - Gestión de interfaz
 */

import { BADGE_COLORS, COLORES, UI_CONFIG, TABS } from './config.js';

export class UIManager {
    static init() {
        console.log('✅ UIManager inicializado');
    }

    static renderBadge(estado) {
        const color = BADGE_COLORS[estado] || '#a0a0a0';
        return `<span style="background: ${color}; color: ${color === '#ffc107' ? 'black' : 'white'}; padding: 0.25rem 0.75rem; border-radius: 20px; font-weight: 600; font-size: 0.8rem;">${estado.toUpperCase()}</span>`;
    }

    static showTab(tabName) {
        const tab = document.querySelector(`[data-bs-target="${TABS[tabName]}"]`);
        if (tab) tab.click();
    }

    static mostrarAlerta(mensaje, tipo = 'info') {
        const prefijo = {
            success: '✅',
            warning: '⚠️',
            error: '❌',
            info: 'ℹ️',
        }[tipo] || '';

        alert(`${prefijo} ${mensaje}`.trim());
    }
}

export default UIManager;
