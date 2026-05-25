/**
 * Facturas Modal - Consistente con Albaranes
 */

import { BADGE_COLORS } from '../config.js';

class FacturasModal {

    static getBadgeClass(estado) {
        return BADGE_COLORS[estado] || 'bg-info';
    }

    static getSVG(type) {
        const svgs = {
            info: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>`,
            chart: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><line x1="12" y1="2" x2="12" y2="22"></line><path d="M17 5H9.5a1.5 1.5 0 0 0-1.5 1.5v12a1.5 1.5 0 0 0 1.5 1.5H17"></path><path d="M7 12l4-4 4 4"></path></svg>`,
            package: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>`,
            alert: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3.05h16.94a2 2 0 0 0 1.71-3.05L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>`,
            creditCard: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>`,
            link: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>`,
            notes: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>`,
            document: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>`,
            arrow: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg>`,
            check: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#90ee90" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>`,
            clock: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fed7aa" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>`,
            warning: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fed7aa" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3.05h16.94a2 2 0 0 0 1.71-3.05L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>`,
            search: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>`,
            close: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>`,
            edit: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>`,
        };

        return svgs[type] || '';
    }

    static cargarRelaciones(tipo, id) {
        console.log('🔗 Cargando relaciones:', tipo, id);

        let endpoint = '';

        if (tipo === 'pedido') endpoint = `/compras/pedidos/${id}/json`;
        else if (tipo === 'albaran') endpoint = `/compras/albaranes/${id}/json`;
        else if (tipo === 'factura') endpoint = `/compras/facturas/${id}/json`;

        fetch(endpoint)
            .then(res => res.json())
            .then(data => {
                console.log('📄 Datos recibidos:', data);
                this.renderizarRelaciones(tipo, data);
            })
            .catch(err => console.error('Error:', err));
    }

    static renderizarRelaciones(tipo, data) {
        const contenedor = document.getElementById('contenedorRelaciones');

        if (!contenedor) {
            console.error('❌ contenedorRelaciones NO encontrado');
            return;
        }

        let html = '';

        if (tipo === 'factura') {
            if (data.albaran_numero && data.albaran_numero !== '—') {
                html += `
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: rgba(230, 57, 70, 0.05); border-radius: 6px; margin-bottom: 0.5rem;">
                        ${this.getSVG('package')}
                        <span style="color: #e63946; font-weight: 600;">Albarán:</span>
                        <span style="color: #f0f0f0;">${data.albaran_numero}</span>
                    </div>
                `;
            }

            if (data.pedido_numero && data.pedido_numero !== '—') {
                html += `
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: rgba(230, 57, 70, 0.05); border-radius: 6px;">
                        ${this.getSVG('document')}
                        <span style="color: #e63946; font-weight: 600;">Pedido:</span>
                        <span style="color: #f0f0f0;">${data.pedido_numero}</span>
                    </div>
                `;
            }
        }

        contenedor.innerHTML = html || '<p style="color: #a0a0a0;">Sin documentos relacionados</p>';
    }

    static mostrar(item) {
        const titulo = `Factura: ${item.numero_factura}`;

        // ✅ USA LOS DATOS DEL BACKEND, NO CALCULES LOCALMENTE
        let total_pagado = parseFloat(item.total_pagado) || 0;
        let total_pendiente = parseFloat(item.pendiente) || 0;
        let total_factura = parseFloat(item.total) || 0;
        let estadoFactura = item.estado || 'abierta';

        console.log('📊 Totales:', { total_factura, total_pagado, total_pendiente, estado: estadoFactura });

        let filas_lineas = '';

        if (item.lineas && item.lineas.length > 0) {
            filas_lineas = item.lineas.map((l, idx) => `
                <tr style="border-bottom: 1px solid rgba(230, 57, 70, 0.1);">
                    <td style="padding: 0.75rem; color: #a0a0a0; text-align: center; font-size: 0.875rem;">${idx + 1}</td>
                    <td style="padding: 0.75rem; color: #f0f0f0; font-weight: 500;">${l.producto_nombre || '—'}</td>
                    <td style="padding: 0.75rem; color: #f0f0f0; text-align: center; font-weight: 500;">${l.cantidad}</td>
                </tr>
            `).join('');
        } else {
            filas_lineas = '<tr><td colspan="6" style="text-align: center; padding: 1rem; color: #a0a0a0;">Sin líneas facturadas</td></tr>';
        }

        let filas_pagos = '';

if (item.pagos && item.pagos.length > 0) {

    filas_pagos = item.pagos.map((p) => {

        const colorEstado = p.estado === 'pagado' ? '#90ee90' : 

                           p.estado === 'en_transito' ? '#fed7aa' : '#a0a0a0';

        const bgEstado = p.estado === 'pagado' ? 'rgba(34, 197, 94, 0.1)' :

                        p.estado === 'en_transito' ? 'rgba(249, 115, 22, 0.1)' : 'rgba(160, 160, 160, 0.1)';

        const iconoEstado = p.estado === 'pagado' ? this.getSVG('check') : 

                           p.estado === 'en_transito' ? this.getSVG('clock') : this.getSVG('warning');

        return `

            <tr style="border-bottom: 1px solid rgba(230, 57, 70, 0.1);">

                <td style="padding: 0.75rem; color: #f0f0f0; font-weight: 500;">${p.metodo_pago || '—'}</td>

                <td style="padding: 0.75rem; color: #f0f0f0; font-weight: 500;">${p.banco || '—'}</td>

                <td style="padding: 0.75rem; color: #90ee90; font-weight: 600; text-align: right;">${(parseFloat(p.monto) || 0).toFixed(2)}€</td>

                <td style="padding: 0.75rem; color: #f0f0f0; text-align: center;">${p.fecha || '—'}</td>

                <td style="padding: 0.75rem; color: #a0a0a0; text-align: center; font-family: monospace; font-size: 0.875rem;">${p.referencia || '—'}</td>

                <td style="padding: 0.75rem; text-align: center;">

                    <span style="display: inline-flex; align-items: center; gap: 0.4rem; color: ${colorEstado}; background: ${bgEstado}; padding: 0.3rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">

                        ${iconoEstado}

                        ${(p.estado || 'pendiente').toUpperCase()}

                    </span>

                </td>

            </tr>

        `;

    }).join('');

} else {

    filas_pagos = '<tr><td colspan="6" style="text-align: center; padding: 1rem; color: #a0a0a0;">Sin pagos registrados</td></tr>';

}

        // ✅ DETERMINAR COLOR DEL BADGE SEGÚN ESTADO
        let colorEstadoBadge = 'bg-warning';
        let styleEstadoBadge = '';

        if (estadoFactura === 'pagada') {
            colorEstadoBadge = 'bg-success';
            styleEstadoBadge = 'background: #90ee90 !important; color: #000 !important;';
        } else if (estadoFactura === 'parcial') {
            colorEstadoBadge = 'bg-info';
            styleEstadoBadge = 'background: #ffc107 !important; color: #000 !important;';
        } else {
            colorEstadoBadge = 'bg-danger';
            styleEstadoBadge = 'background: #e63946 !important; color: #fff !important;';
        }

        const contenido = `
            <!-- INFORMACIÓN GENERAL Y ESTADO -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(230, 57, 70, 0.2);">

                <div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                        ${this.getSVG('info')}
                        <h6 style="margin: 0; color: #e63946; font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Información General</h6>
                    </div>
                    <div style="display: grid; gap: 1rem;">
                        <div>
                            <div style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem; font-weight: 600;">Proveedor</div>
                            <div style="color: #f0f0f0; font-weight: 500; font-size: 1rem;">${item.proveedor || '—'}</div>
                        </div>
                        <div>
                            <div style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem; font-weight: 600;">Fecha Factura</div>
                            <div style="color: #f0f0f0; font-weight: 500;">${item.fecha_factura || '—'}</div>
                        </div>
                        <div>
                            <div style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem; font-weight: 600;">Fecha Vencimiento</div>
                            <div style="color: #f0f0f0; font-weight: 500;">${item.fecha_vencimiento || '—'}</div>
                        </div>
                    </div>
                </div>

                <div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                        ${this.getSVG('chart')}
                        <h6 style="margin: 0; color: #e63946; font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Estado y Totales</h6>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <div style="font-size: 0.75rem; color: #a0a0a0; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Estado</div>
                        <span class="badge ${colorEstadoBadge} px-3 py-2" style="font-weight: 600; font-size: 0.8rem; ${styleEstadoBadge}">
                            ${(estadoFactura).toUpperCase()}
                        </span>
                    </div>
                    <div style="background: rgba(230, 57, 70, 0.08); padding: 1rem; border-radius: 6px; border-left: 3px solid #e63946;">
                        <div style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem; font-weight: 600;">Total</div>
                        <div style="color: #e63946; font-weight: 700; font-size: 1.75rem;">${(total_factura).toFixed(2)}€</div>
                    </div>
                </div>

            </div>

            <!-- LÍNEAS FACTURADAS -->
<div style="margin-bottom: 1.5rem;">
    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
        ${this.getSVG('package')}
        <h6 style="margin: 0; color: #e63946; font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Líneas Facturadas</h6>
    </div>
    <div style="overflow-x: auto; border-radius: 8px; border: 1px solid rgba(230, 57, 70, 0.2);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: rgba(230, 57, 70, 0.15);">
                    <th style="padding: 0.75rem; color: #e63946; text-align: center; font-weight: 700; font-size: 0.875rem;">#</th>
                    <th style="padding: 0.75rem; color: #e63946; text-align: left; font-weight: 700; font-size: 0.875rem;">PRODUCTO</th>
                    <th style="padding: 0.75rem; color: #e63946; text-align: center; font-weight: 700; font-size: 0.875rem;">CANTIDAD RECIBIDA</th>
                </tr>
            </thead>
            <tbody>
                ${filas_lineas}
            </tbody>
        </table>
    </div>
</div>

            <!-- FORMAS DE PAGO -->
<div style="margin-bottom: 1.5rem;">
    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
        ${this.getSVG('creditCard')}
        <h6 style="margin: 0; color: #e63946; font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Formas de Pago</h6>
    </div>
    <div style="overflow-x: auto; border-radius: 8px; border: 1px solid rgba(230, 57, 70, 0.2);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: rgba(230, 57, 70, 0.15);">
                    <th style="padding: 0.75rem; color: #e63946; text-align: left; font-weight: 700; font-size: 0.875rem;">MÉTODO</th>
                    <th style="padding: 0.75rem; color: #e63946; text-align: left; font-weight: 700; font-size: 0.875rem;">BANCO</th>
                    <th style="padding: 0.75rem; color: #e63946; text-align: right; font-weight: 700; font-size: 0.875rem;">MONTO</th>
                    <th style="padding: 0.75rem; color: #e63946; text-align: center; font-weight: 700; font-size: 0.875rem;">FECHA</th>
                    <th style="padding: 0.75rem; color: #e63946; text-align: center; font-weight: 700; font-size: 0.875rem;">REFERENCIA</th>
                    <th style="padding: 0.75rem; color: #e63946; text-align: center; font-weight: 700; font-size: 0.875rem;">ESTADO</th>
                </tr>
            </thead>
            <tbody>
                ${filas_pagos}
            </tbody>
        </table>
    </div>

                ${item.pagos && item.pagos.length > 0 ? `
                    <div style="margin-top: 1rem; padding: 1rem; background: rgba(230, 57, 70, 0.05); border-radius: 6px; border-left: 3px solid #e63946;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                            <div>
                                <div style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem; font-weight: 600;">TOTAL COMPROMETIDO</div>
                                <div style="color: #e63946; font-weight: 700; font-size: 1.5rem;">${(total_factura).toFixed(2)}€</div>
                            </div>
                            <div>
                                <div style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem; font-weight: 600;">💰 PAGADO</div>
                                <div style="color: #90ee90; font-weight: 700; font-size: 1.5rem;">${(total_pagado).toFixed(2)}€</div>
                            </div>
                            <div>
                                <div style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem; font-weight: 600;">⏳ PENDIENTE</div>
                                <div style="color: #ffc107; font-weight: 700; font-size: 1.5rem;">${(total_pendiente).toFixed(2)}€</div>
                            </div>
                        </div>
                    </div>
                ` : ''}

            </div>

            <!-- OBSERVACIONES -->
            ${item.observaciones ? `
                <div style="padding: 1rem; background: rgba(230, 57, 70, 0.05); border-radius: 8px; border-left: 3px solid #e63946; margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                        ${this.getSVG('notes')}
                        <div style="font-size: 0.875rem; font-weight: 700; color: #e63946; text-transform: uppercase;">Observaciones</div>
                    </div>
                    <div style="color: #f0f0f0; line-height: 1.5;">${item.observaciones}</div>
                </div>
            ` : ''}

            <!-- DOCUMENTOS RELACIONADOS -->
            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid rgba(230, 57, 70, 0.3);">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                    ${this.getSVG('link')}
                    <h6 style="margin: 0; color: #e63946; font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Documentos Relacionados</h6>
                </div>
                <div id="contenedorRelaciones" style="display: grid; gap: 0.5rem;">
                    <p style="color: #a0a0a0; font-size: 0.875rem;">Cargando documentos...</p>
                </div>
            </div>
        `;

        this.crearYMostrarModal(titulo, contenido, item.id);
    }

    static crearYMostrarModal(titulo, contenido, itemId) {
        const modalHTML = `
            <div class="modal fade" id="detalleFacturaModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3);">
                        <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946; padding: 1rem;">
                            <h5 class="modal-title" style="color: #e63946; font-weight: 700; display: flex; align-items: center; gap: 0.75rem;">
                                ${this.getSVG('search')}
                                ${titulo}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" style="padding: 1.5rem; color: #f0f0f0;">
                            ${contenido}
                        </div>
                        <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3); padding: 1rem;">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="display: flex; align-items: center; gap: 0.5rem;">
                                ${this.getSVG('close')}
                                Cerrar
                            </button>
                            <button type="button" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                                ${this.getSVG('edit')}
                                Editar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const modalAnterior = document.getElementById('detalleFacturaModal');
        if (modalAnterior) modalAnterior.remove();

        document.body.insertAdjacentHTML('beforeend', modalHTML);

        const modal = new bootstrap.Modal(document.getElementById('detalleFacturaModal'));
        modal.show();

        document.getElementById('detalleFacturaModal').addEventListener('hidden.bs.modal', function() {
            this.remove();
        });

        setTimeout(() => {
            this.cargarRelaciones('factura', itemId);
        }, 300);
    }
}

export default FacturasModal;