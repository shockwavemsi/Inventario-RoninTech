/**
 * Pedidos Modal - Consistente con Albaranes y Facturas
 */

import { BADGE_COLORS } from '../config.js';

class PedidosModal {

    static getBadgeClass(estado) {
        return BADGE_COLORS[estado] || 'bg-info';
    }

    // ✅ TODOS LOS SVG CON stroke="#e63946" ROJO FIJO
    static getSVG(type) {
        const svgs = {
             info: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>`,

            chart: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="2" x2="12" y2="22"></line><path d="M17 5H9.5a1.5 1.5 0 0 0-1.5 1.5v12a1.5 1.5 0 0 0 1.5 1.5H17"></path><path d="M7 12l4-4 4 4"></path></svg>`,

            package: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>`,

            link: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>`,

            notes: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>`,

            document: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>`,

            search: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>`,

            albaran: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l-5.5 9h11z"></path><path d="M6.5 11h11l-5.5 9z"></path></svg>`,

            factura: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>`,

            pedido: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"></path><path d="M20 21H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2z"></path></svg>`,

            close: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>`,

            edit: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>`,
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
                console.log('📦 Datos recibidos:', data);
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

        if (tipo === 'pedido') {
            if (data.albaranes && data.albaranes.length) {
                data.albaranes.forEach(a => {
                    html += `
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: rgba(230, 57, 70, 0.05); border-radius: 6px; margin-bottom: 0.5rem;">
                            ${this.getSVG('albaran')}
                            <span style="color: #e63946; font-weight: 600;">Albarán:</span>
                            <span style="color: #f0f0f0;">${a.numero_albaran}</span>
                        </div>
                    `;
                });
            }

            if (data.facturas && data.facturas.length) {
                data.facturas.forEach(f => {
                    html += `
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: rgba(230, 57, 70, 0.05); border-radius: 6px; margin-bottom: 0.5rem;">
                            ${this.getSVG('factura')}
                            <span style="color: #e63946; font-weight: 600;">Factura:</span>
                            <span style="color: #f0f0f0;">${f.numero_factura}</span>
                        </div>
                    `;
                });
            }
        }

        contenedor.innerHTML = html || '<p style="color: #a0a0a0;">Sin documentos relacionados</p>';
    }

    static mostrar(item) {
        const titulo = `Pedido: ${item.numero_pedido}`;

        let filas_tabla = '';
        if (item.detalles && item.detalles.length > 0) {
            filas_tabla = item.detalles.map((d, idx) => `
                <tr style="border-bottom: 1px solid rgba(230, 57, 70, 0.1);">
                    <td style="padding: 0.75rem; color: #a0a0a0; text-align: center; font-size: 0.875rem;">${idx + 1}</td>
                    <td style="padding: 0.75rem; color: #f0f0f0; font-weight: 500;">${d.producto_nombre}</td>
                    <td style="padding: 0.75rem; color: #f0f0f0; text-align: center; font-weight: 500;">${d.cantidad}</td>
                    <td style="padding: 0.75rem; color: #f0f0f0; text-align: right;">${parseFloat(d.precio_unitario).toFixed(2)}€</td>
                    <td style="padding: 0.75rem; color: #90ee90; font-weight: 600; text-align: right;">${parseFloat(d.precio_por_linea).toFixed(2)}€</td>
                </tr>
            `).join('');
        }

        const contenido = `
            <!-- INFORMACIÓN GENERAL Y ESTADO (GRID 1fr 1fr) -->
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
                            <div style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem; font-weight: 600;">Fecha Pedido</div>
                            <div style="color: #f0f0f0; font-weight: 500;">${item.fecha_pedido || '—'}</div>
                        </div>
                        <div>
                            <div style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem; font-weight: 600;">Fecha Entrega Esperada</div>
                            <div style="color: #f0f0f0; font-weight: 500;">${item.fecha_entrega_esperada || '—'}</div>
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
                        <span class="badge ${this.getBadgeClass(item.estado)} px-3 py-2" style="font-weight: 600; font-size: 0.8rem;">
                            ${(item.estado || 'Desconocido').toUpperCase()}
                        </span>
                    </div>

                    <div style="background: rgba(230, 57, 70, 0.08); padding: 1rem; border-radius: 6px; border-left: 3px solid #e63946;">
                        <div style="display: grid; gap: 0.6rem; font-size: 0.9rem;">
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #a0a0a0;">SUBTOTAL</span>
                                <span style="color: #f0f0f0; font-weight: 600;">${parseFloat(item.subtotal_total).toFixed(2)}€</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding-top: 0.5rem; border-top: 1px solid rgba(230, 57, 70, 0.2);">
                                <span style="color: #a0a0a0;">DESCUENTO</span>
                                <span style="color: #90ee90;">-${parseFloat(item.descuento_total).toFixed(2)}€</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding-top: 0.75rem; border-top: 2px solid #e63946; margin-top: 0.75rem;">
                                <span style="color: #e63946; font-weight: 700;">TOTAL</span>
                                <span style="color: #e63946; font-weight: 700; font-size: 1.3rem;">${parseFloat(item.total_final).toFixed(2)}€</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PRODUCTOS COMPRADOS -->
            <div style="margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                    ${this.getSVG('package')}
                    <h6 style="margin: 0; color: #e63946; font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Productos Comprados</h6>
                </div>

                <div style="overflow-x: auto; border-radius: 8px; border: 1px solid rgba(230, 57, 70, 0.2);">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: rgba(230, 57, 70, 0.15);">
                                <th style="padding: 0.75rem; color: #e63946; text-align: center; font-weight: 700; font-size: 0.875rem;">#</th>
                                <th style="padding: 0.75rem; color: #e63946; text-align: left; font-weight: 700; font-size: 0.875rem;">PRODUCTO</th>
                                <th style="padding: 0.75rem; color: #e63946; text-align: center; font-weight: 700; font-size: 0.875rem;">CANTIDAD</th>
                                <th style="padding: 0.75rem; color: #e63946; text-align: right; font-weight: 700; font-size: 0.875rem;">PRECIO UNIT.</th>
                                <th style="padding: 0.75rem; color: #e63946; text-align: right; font-weight: 700; font-size: 0.875rem;">SUBTOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${filas_tabla || '<tr><td colspan="5" style="text-align: center; padding: 1rem; color: #a0a0a0;">Sin detalles</td></tr>'}
                        </tbody>
                    </table>
                </div>
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
            <div class="modal fade" id="detalleModal_${itemId}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3);">
                        <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946; padding: 1rem;">
                            <h5 class="modal-title" style="color: #e63946; font-weight: 700; font-size: 1.1rem; letter-spacing: 0.5px; display: flex; align-items: center; gap: 0.75rem;">
                                ${this.getSVG('search')}
                                ${titulo}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body" style="padding: 1.5rem; color: #f0f0f0;">
                            ${contenido}
                        </div>
                    </div>
                </div>
            </div>
        `;

        const modalId = `detalleModal_${itemId}`;
        window.modalManager.openModal(modalId, modalHTML);

        setTimeout(() => {
            this.cargarRelaciones('pedido', itemId);
        }, 300);
    }

}

window.PedidosModal = PedidosModal;
export default PedidosModal;