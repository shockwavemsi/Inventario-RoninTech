/**
 * Compras - Orquestador principal
 */

import { TABS, BADGE_COLORS, COLORS, UI_CONFIG } from './config.js';
import ComprasUI from './ui.js';
import ComprasAPI from './api.js';
import ComprasModal from './modal.js';
import PedidosModal from './modales/pedidos-modal.js';
import AlbaranesModal from './modales/albaranes-modal.js';
import FacturasModal from './modales/facturas-modal.js';
import CrearPedidoModal from './modales/crear-pedido-modal.js';
import CrearAlbaranModal from './modales/crear-albaran-modal.js';
import CrearFacturaModal from './modales/crear-factura-modal.js';

export class ComprasApp {

    constructor() {
        this.ui = new ComprasUI();
        this.api = new ComprasAPI();
    }

    async init() {
        this.ui.renderFlow((tabId) => this.switchTab(tabId));
        await this.loadData('pedidos');
    }

    async switchTab(tabId) {
        this.ui.setActiveTab(tabId);
        this.ui.renderFlow((id) => this.switchTab(id));
        await this.loadData(tabId);
    }

    async loadData(tabId) {
        this.ui.showLoading();
        const data = await this.api.fetchTab(tabId);
        if (data) {
            this.ui.renderContent(tabId, data);
        } else {
            this.ui.showError('Error al cargar los datos');
        }
    }

    abrirDetalles(id, tipo) {
        ComprasModal.mostrar(id, tipo);
    }
}

// ============ INSTANCIAR Y EXPONER GLOBALMENTE ============

window.comprasApp = new ComprasApp();

// ✅ EXPONER CLASES MODALES A WINDOW
window.CrearPedidoModal = CrearPedidoModal;
window.PedidosModal = PedidosModal;
window.CrearAlbaranModal = CrearAlbaranModal;  // ← ESTA LÍNEA ES CRÍTICA
window.AlbaranesModal = AlbaranesModal;
window.FacturasModal = FacturasModal;

// ============ INICIALIZAR APP ============
window.comprasApp.init();

// ============ FUNCIONES GLOBALES PARA ABRIR MODALES ============

// ✅ CREAR NUEVO DOCUMENTO (INTELIGENTE SEGÚN TAB)
window.abrirCrearDocumento = function(tabId) {
    console.log('🔍 abrirCrearDocumento llamado con:', tabId);

    if (tabId === 'pedidos') {
        console.log('📝 Abriendo modal crear pedido...');
        window.CrearPedidoModal?.mostrar?.();

    } else if (tabId === 'albaranes') {
        console.log('📦 Abriendo modal crear albarán...');
        console.log('¿CrearAlbaranModal existe?', !!window.CrearAlbaranModal);

        if (window.CrearAlbaranModal) {
            console.log('✅ Llamando mostrar()...');
            window.CrearAlbaranModal.mostrar();
        } else {
            console.error('❌ CrearAlbaranModal NO está en window');
            alert('⚠️ Módulo de albarán no cargado');
        }

    } else if (tabId === 'facturas') {
        console.log('📄 Abriendo modal crear factura...');
        window.CrearFacturaModal?.mostrar?.();
    }
};

// ✅ VER DETALLES PEDIDO
window.abrirModalPedido = function(id) {
    console.log('👁️ Abriendo detalles pedido:', id);
    window.comprasApp.abrirDetalles(id, 'pedido');
};

window.clonarPedidoCompra = async function(id) {
    if (!id) return;

    const confirmar = confirm('�Clonar este pedido con el mismo proveedor y productos?');
    if (!confirmar) return;

    try {
        const response = await fetch(`/compras/pedidos/${id}/clonar`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
            },
        });

        const data = await response.json();
        if (!response.ok || !data.success) {
            throw new Error(data.message || 'No se pudo clonar el pedido');
        }

        alert(data.message || 'Pedido clonado correctamente');
        await window.comprasApp.loadData('pedidos');

    } catch (error) {
        console.error('Error al clonar pedido:', error);
        alert(error.message || 'Error al clonar el pedido');
    }
};

// ✅ VER DETALLES ALBARÁN
window.abrirModalAlbaran = function(id) {
    console.log('👁️ Abriendo detalles albarán:', id);
    window.comprasApp.abrirDetalles(id, 'albaran');
};

// ✅ VER DETALLES FACTURA
window.abrirModalFactura = function(id) {
    console.log('👁️ Abriendo detalles factura:', id);
    window.comprasApp.abrirDetalles(id, 'factura');
};

// ============ CARGAR RELACIONES ============

window.cargarRelaciones = function(tipo, id) {
    let endpoint = '';
    if (tipo === 'pedido') endpoint = `/compras/pedidos/${id}/json`;
    else if (tipo === 'albaran') endpoint = `/compras/albaranes/${id}/json`;
    else if (tipo === 'factura') endpoint = `/compras/facturas/${id}/json`;

    fetch(endpoint)
        .then(res => res.json())
        .then(data => window.renderizarRelaciones(tipo, data))
        .catch(err => console.error('Error:', err));
};

// ============ RENDERIZAR RELACIONES ============

window.renderizarRelaciones = function(tipo, data) {
    const contenedor = document.getElementById('contenedorRelaciones');
    if (!contenedor) {
        console.warn('⚠️ contenedorRelaciones no encontrado');
        return;
    }

    let html = '';

    if (tipo === 'pedido') {
        if (data.albaranes && data.albaranes.length) {
            html += `<div style="color: #a0a0a0; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 0.5rem;"><strong>📦 Albaranes</strong></div>`;
            data.albaranes.forEach(a => {
                html += `
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: rgba(230, 57, 70, 0.1); border-radius: 6px; margin-bottom: 0.5rem;">
                        <span style="color: #f0f0f0;">${a.numero_albaran}</span>
                        <button class="btn btn-sm btn-link p-0" onclick="window.abrirModalAlbaran(${a.id})" style="cursor: pointer; color: #e63946;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                `;
            });
        }

        if (data.facturas && data.facturas.length) {
            html += `<div style="color: #a0a0a0; font-size: 0.75rem; text-transform: uppercase; margin-top: 1rem; margin-bottom: 0.5rem;"><strong>📄 Facturas</strong></div>`;
            data.facturas.forEach(f => {
                html += `
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: rgba(230, 57, 70, 0.1); border-radius: 6px; margin-bottom: 0.5rem;">
                        <span style="color: #f0f0f0;">${f.numero_factura}</span>
                        <button class="btn btn-sm btn-link p-0" onclick="window.abrirModalFactura(${f.id})" style="cursor: pointer; color: #e63946;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                `;
            });
        }
    }

    else if (tipo === 'albaran') {
        if (data.pedido_id) {
            html += `
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: rgba(230, 57, 70, 0.1); border-radius: 6px; margin-bottom: 0.5rem;">
                    <span style="color: #f0f0f0;"><strong>Pedido:</strong> ${data.pedido_numero}</span>
                    <button class="btn btn-sm btn-link p-0" onclick="window.abrirModalPedido(${data.pedido_id})" style="cursor: pointer; color: #e63946;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            `;
        }

        if (data.facturas && data.facturas.length) {
            html += `<div style="color: #a0a0a0; font-size: 0.75rem; text-transform: uppercase; margin-top: 1rem; margin-bottom: 0.5rem;"><strong>📄 Facturas</strong></div>`;
            data.facturas.forEach(f => {
                html += `
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: rgba(230, 57, 70, 0.1); border-radius: 6px; margin-bottom: 0.5rem;">
                        <span style="color: #f0f0f0;">${f.numero_factura}</span>
                        <button class="btn btn-sm btn-link p-0" onclick="window.abrirModalFactura(${f.id})" style="cursor: pointer; color: #e63946;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                `;
            });
        }
    }

    else if (tipo === 'factura') {
        if (data.albaran_id) {
            html += `
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: rgba(230, 57, 70, 0.1); border-radius: 6px; margin-bottom: 0.5rem;">
                    <span style="color: #f0f0f0;"><strong>Albarán:</strong> ${data.albaran_numero}</span>
                    <button class="btn btn-sm btn-link p-0" onclick="window.abrirModalAlbaran(${data.albaran_id})" style="cursor: pointer; color: #e63946;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            `;
        }

        if (data.pedido_id) {
            html += `
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: rgba(230, 57, 70, 0.1); border-radius: 6px; margin-bottom: 0.5rem;">
                    <span style="color: #f0f0f0;"><strong>Pedido:</strong> ${data.pedido_numero}</span>
                    <button class="btn btn-sm btn-link p-0" onclick="window.abrirModalPedido(${data.pedido_id})" style="cursor: pointer; color: #e63946;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            `;
        }
    }

    contenedor.innerHTML = html || '<p style="color: #a0a0a0; font-size: 0.875rem;">Sin documentos relacionados</p>';
};

export default ComprasApp;

