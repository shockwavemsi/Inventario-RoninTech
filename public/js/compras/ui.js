/**
 * UI - Renderizado y manipulación del DOM
 */

import { TABS, BADGE_COLORS, UI_CONFIG } from './config.js';

class ComprasUI {

    constructor() {
        this.activeTab = 'pedidos';
        this.injectStyles();
    }

    injectStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .card-item {
                cursor: pointer;
                transition: all 0.3s;
            }

            .card-item:hover {
                background-color: rgba(20, 20, 25, 0.85) !important;
                box-shadow: 0 8px 32px rgba(230, 57, 70, 0.2) !important;
            }

            .flow-step { 
                cursor: pointer; 
                transition: all 0.3s; 
            }

            .flow-step:hover { 
                opacity: 1 !important; 
            }

            @keyframes slideInUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            .modal-content {
                background-color: rgba(20, 20, 25, 0.95) !important;
                border: 1px solid rgba(230, 57, 70, 0.3) !important;
            }

            .modal-header {
                background: rgba(230, 57, 70, 0.15) !important;
                border-bottom: 2px solid #e63946 !important;
            }

            .modal-body {
                color: #f0f0f0 !important;
            }
        `;
        document.head.appendChild(style);
    }

    getBadgeClass(estado) {
        return BADGE_COLORS[estado] || 'bg-info';
    }

    getSVG(type) {
        const svgs = {
            building: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>`,
            calendar: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>`,
            file: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>`,
            clock: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>`,
            checkCircle: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#90ee90" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>`,
            package: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>`,
            hourglass: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><path d="M6 2h12v6l-6 6-6-6V2z"></path><path d="M6 14h12v6l-6 6-6-6v-6z"></path></svg>`,
        };
        return svgs[type] || '';
    }

    renderFlow(onTabClick) {
        const flowSteps = document.getElementById('flowSteps');
        flowSteps.innerHTML = '';
        TABS.forEach((tab, idx) => {
            const step = document.createElement('div');
            step.className = 'flow-step';
            step.style.cssText = `
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.5rem;
                opacity: ${tab.id === this.activeTab ? '1' : '0.6'};
            `;
            step.onclick = () => onTabClick(tab.id);
            step.innerHTML = `
                <div style="
                    width: 56px;
                    height: 56px;
                    background-color: #e63946;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-size: 24px;
                    box-shadow: 0 4px 15px rgba(230, 57, 70, 0.3);
                    transform: ${tab.id === this.activeTab ? 'scale(1.15)' : 'scale(1)'};
                    transition: transform 0.3s;
                ">
                    <i class="bi bi-${tab.icon}"></i>
                </div>
                <div style="font-size: 0.875rem; font-weight: 600; color: #a0a0a0;">${tab.label}</div>
            `;
            flowSteps.appendChild(step);

            if (idx < TABS.length - 1) {
                const connector = document.createElement('div');
                connector.style.cssText = `
                    flex: 1;
                    height: 3px;
                    background: linear-gradient(to right, #e63946, #991b1b);
                    min-width: 30px;
                    margin-bottom: 2rem;
                `;
                flowSteps.appendChild(connector);
            }
        });
    }

    renderContent(tabId, data) {
        const mainContent = document.getElementById('mainContent');
        const tab = TABS.find(t => t.id === tabId);
        if (!tab) return;

        const titulo = {
            'pedidos': 'PEDIDOS DE COMPRA',
            'albaranes': 'ALBARANES DE COMPRA',
            'facturas': 'FACTURAS DE COMPRA'
        }[tabId];

        const textoBoton = {
            'pedidos': 'Crear Nuevo Pedido',
            'albaranes': 'Crear Nuevo Albarán',
            'facturas': 'Crear Nueva Factura'
        }[tabId];

        let html = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; animation: fadeIn 0.3s ease-in;">
                <h2 style="font-size: 1.875rem; font-weight: bold; color: #e63946; margin: 0;">
                    <i class="bi bi-${tab.icon}" style="margin-right: 0.5rem;"></i>${titulo}
                </h2>
                <button class="btn btn-primary" onclick="window.abrirCrearDocumento('${tabId}')" style="background: #e63946; border: none;">
                    <i class="bi bi-plus-lg"></i> ${textoBoton}
                </button>
            </div>
            <div style="display: grid; gap: 1rem;">
        `;

        if (!data || data.length === 0) {
            html += `
                <div style="text-align: center; padding: 3rem; color: #a0a0a0;">
                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                    <p>Sin registros en esta sección</p>
                </div>
            `;
        } else {
            data.forEach((item, idx) => {
                html += this.renderCard(item, tabId, idx);
            });
        }

        html += `</div>`;
        mainContent.innerHTML = html;
        this.attachCardListeners(tabId);
    }

    renderCard(item, tabId, idx) {
        const badgeEstado = this.getBadgeClass(item.estado);
        let infoHtml = '';

        if (tabId === 'pedidos') {
            infoHtml = `
                <div style="color: #a0a0a0; font-size: 0.875rem; margin: 0.5rem 0;">
                    <span style="display: flex; align-items: center; gap: 0.5rem; color: #e63946;">
                        ${this.getSVG('building')}
                        <strong style="color: #f0f0f0;">${item.proveedor || '—'}</strong>
                    </span>
                </div>
                <div style="color: #a0a0a0; font-size: 0.875rem; margin: 0.5rem 0;">
                    <span style="display: flex; align-items: center; gap: 0.5rem; color: #e63946;">
                        ${this.getSVG('calendar')}
                        <strong style="color: #f0f0f0;">${item.fecha_pedido || '—'}</strong>
                    </span>
                </div>
            `;
        } else if (tabId === 'albaranes') {
            infoHtml = `
                <div style="color: #a0a0a0; font-size: 0.875rem; margin: 0.5rem 0;">
                    <span style="display: flex; align-items: center; gap: 0.5rem; color: #e63946;">
                        ${this.getSVG('file')}
                        <strong style="color: #f0f0f0;">${item.pedido || '—'}</strong>
                    </span>
                </div>
                <div style="color: #a0a0a0; font-size: 0.875rem; margin: 0.5rem 0;">
                    <span style="display: flex; align-items: center; gap: 0.5rem; color: #e63946;">
                        ${this.getSVG('calendar')}
                        <strong style="color: #f0f0f0;">${item.fecha_albaran || '—'}</strong>
                    </span>
                </div>
                <div style="color: #a0a0a0; font-size: 0.875rem; margin: 0.5rem 0;">
                    <span style="display: flex; align-items: center; gap: 0.5rem; color: #e63946;">
                        ${this.getSVG('hourglass')}
                        <strong style="color: #f0f0f0;">Recepción: ${item.fecha_recepcion || '—'}</strong>
                    </span>
                </div>
            `;
        } else if (tabId === 'facturas') {
            const estadoPago = item.pagos && item.pagos.length > 0 ? item.pagos[0].estado : null;
            const colorPago = estadoPago === 'pagado' ? '#90ee90' : '#fed7aa';
            const bgPago = estadoPago === 'pagado' ? 'rgba(34, 197, 94, 0.1)' : 'rgba(254, 215, 170, 0.15)';
            const borderPago = estadoPago === 'pagado' ? '#90ee90' : '#fed7aa';
            const iconoPago = estadoPago === 'pagado' ? this.getSVG('checkCircle') : this.getSVG('clock');

            infoHtml = `
                <div style="color: #a0a0a0; font-size: 0.875rem; margin: 0.5rem 0;">
                    <span style="display: flex; align-items: center; gap: 0.5rem; color: #e63946;">
                        ${this.getSVG('building')}
                        <strong style="color: #f0f0f0;">${item.proveedor || '—'}</strong>
                    </span>
                </div>
                <div style="color: #a0a0a0; font-size: 0.875rem; margin: 0.5rem 0;">
                    <span style="display: flex; align-items: center; gap: 0.5rem; color: #e63946;">
                        ${this.getSVG('package')}
                        <strong style="color: #f0f0f0;">${item.albaran || '—'}</strong>
                    </span>
                </div>
                <div style="color: #a0a0a0; font-size: 0.875rem; margin: 0.5rem 0;">
                    <span style="display: flex; align-items: center; gap: 0.5rem; color: #e63946;">
                        ${this.getSVG('calendar')}
                        <strong style="color: #f0f0f0;">Factura: ${item.fecha_factura || '—'}</strong>
                    </span>
                </div>
                <div style="color: #a0a0a0; font-size: 0.875rem; margin: 0.5rem 0;">
                    <span style="display: flex; align-items: center; gap: 0.5rem; color: #e63946;">
                        ${this.getSVG('calendar')}
                        <strong style="color: #f0f0f0;">Vence: ${item.fecha_vencimiento || '—'}</strong>
                    </span>
                </div>
                ${estadoPago ? `
                    <div style="margin-top: 0.75rem; padding: 0.5rem; background: ${bgPago}; border-radius: 4px; border-left: 3px solid ${borderPago};">
                        <span style="display: flex; align-items: center; gap: 0.5rem; color: ${colorPago}; font-weight: 600; font-size: 0.875rem;">
                            ${iconoPago}
                            <strong>${estadoPago.toUpperCase()}</strong>
                        </span>
                    </div>
                ` : ''}
            `;
        }

        return `
            <div class="card-item" data-item-id="${item.id}" data-item-type="${item.type}" style="
                background-color: rgba(20, 20, 25, 0.65);
                border-left: 4px solid #e63946;
                border-radius: 8px;
                padding: 1.5rem;
                backdrop-filter: blur(20px);
                border: 1px solid rgba(230, 57, 70, 0.25);
                transition: all 0.3s;
                animation: slideInUp 0.4s ease-out forwards;
                animation-delay: ${idx * UI_CONFIG.animationDelay}ms;
            ">
                <div style="display: flex; justify-content: space-between; gap: 2rem; align-items: flex-start;">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                            <strong style="font-size: 1.125rem; color: #e63946;">${item.numero_pedido || item.numero_albaran || item.numero_factura}</strong>
                            <span class="badge ${badgeEstado} px-3 py-2" style="font-weight: 600; text-transform: uppercase; font-size: 0.7rem;">
                                ${(item.estado || 'Desconocido').toUpperCase()}
                            </span>
                        </div>
                        ${infoHtml}
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 2rem; font-weight: bold; color: #e63946;">${(item.total || 0).toFixed(2)}€</div>
                        <div style="color: #a0a0a0; font-size: 0.75rem; margin-top: 0.25rem;">Total</div>
                    </div>
                </div>
            </div>
        `;
    }

    attachCardListeners(tabId) {
        const cards = document.querySelectorAll('.card-item');
        cards.forEach(card => {
            card.addEventListener('click', () => {
                const id = card.getAttribute('data-item-id');
                const tipo = card.getAttribute('data-item-type');
                window.comprasApp.abrirDetalles(id, tipo);
            });
        });
    }

    setActiveTab(tabId) {
        this.activeTab = tabId;
    }

    showError(message) {
        const mainContent = document.getElementById('mainContent');
        mainContent.innerHTML = `
            <div style="text-align: center; padding: 3rem; color: #e63946;">
                <i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>
                <p>${message}</p>
            </div>
        `;
    }

    showLoading() {
        const mainContent = document.getElementById('mainContent');
        mainContent.innerHTML = `
            <div style="text-align: center; padding: 3rem;">
                <div class="spinner-border text-danger" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>
        `;
    }
}

export default ComprasUI;