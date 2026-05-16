/**
 * Modal Ver Venta - Mostrar detalles de una venta
 */
window.ModalVerVenta = {
    ventaActual: null,
    modal: null,

    init() {
        this.modal = document.getElementById('modalVerVenta') 
            ? new bootstrap.Modal(document.getElementById('modalVerVenta'))
            : null;
        console.log('✅ ModalVerVenta inicializado');
    },

    async mostrar(ventaId) {
        console.log('👁️ Abriendo venta:', ventaId);

        try {
            // Obtener datos de la venta
            const response = await fetch(`/ventas/${ventaId}/json`);

            if (!response.ok) throw new Error('Error al obtener venta');

            const venta = await response.json();
            console.log('✅ Venta cargada:', venta);

            this.ventaActual = venta;
            this.renderizar(venta);
            this.modal.show();

        } catch (error) {
            console.error('❌ Error:', error);
            alert('❌ Error al cargar la venta');
        }
    },

    renderizar(venta) {
        // INFORMACIÓN GENERAL
        document.getElementById('detalleNumero').textContent = venta.numero_factura || 'V-001';
        document.getElementById('detalleCliente').textContent = venta.cliente || '—';
        document.getElementById('detalleDocumento').textContent = venta.cliente_documento || '—';
        document.getElementById('detalleFecha').textContent = window.formatearFecha(venta.fecha_venta);

        // ESTADO Y DETALLES
        const estadoMap = {
            'completada': { label: 'Completada', color: '#90ee90' },
            'pendiente': { label: 'Pendiente', color: '#ffc107' },
            'cancelada': { label: 'Cancelada', color: '#dc3545' },
        };

        const estadoObj = estadoMap[venta.estado] || { label: venta.estado, color: '#a0a0a0' };
        const estatusBadge = document.getElementById('detalleEstado');
        estatusBadge.textContent = estadoObj.label.toUpperCase();
        estatusBadge.style.background = estadoObj.color;
        estatusBadge.style.color = estadoObj.color === '#ffc107' ? 'black' : (estadoObj.color === '#90ee90' ? 'black' : 'white');

        document.getElementById('detalleMetodo').textContent = this.mapearMetodo(venta.metodo_pago);
        document.getElementById('detalleUsuario').textContent = venta.usuario?.nombre || 'User';

        // PRODUCTOS
        this.renderizarProductos(venta.detalles || []);

        // OBSERVACIONES
        document.getElementById('detalleObservaciones').textContent = venta.observaciones || '—';
    },

    renderizarProductos(detalles) {
        const tbody = document.getElementById('detalleProductos');
        tbody.innerHTML = '';

        let subtotal = 0;

        if (detalles.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #a0a0a0;">Sin productos</td></tr>';
            return;
        }

        detalles.forEach(detalle => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${detalle.producto?.nombre || 'Producto'}</td>
                <td style="text-align: center;">${detalle.cantidad}</td>
                <td style="text-align: right;">€${parseFloat(detalle.precio_unitario).toFixed(2)}</td>
                <td style="text-align: right; color: #e63946; font-weight: 600;">€${parseFloat(detalle.subtotal).toFixed(2)}</td>
            `;
            tbody.appendChild(tr);
            subtotal += parseFloat(detalle.subtotal);
        });

        // TOTALES
        const iva = subtotal * 0.21;
        const total = subtotal + iva;

        document.getElementById('detalleSub').textContent = '€' + subtotal.toFixed(2);
        document.getElementById('detalleIva').textContent = '€' + iva.toFixed(2);
        document.getElementById('detalleTotal').textContent = '€' + total.toFixed(2);
    },

    mapearMetodo(metodo) {
        const mapa = {
            'efectivo': 'Efectivo',
            'tarjeta': 'Tarjeta de Crédito',
            'transferencia': 'Transferencia',
            'credito': 'Crédito',
        };
        return mapa[metodo] || metodo;
    },

    imprimir() {
        if (!this.ventaActual) return;

        const ventana = window.open('', '', 'width=800,height=600');

        const contenido = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Venta ${this.ventaActual.numero_factura}</title>
                <style>
                    body { font-family: Arial; margin: 20px; }
                    h2 { color: #333; border-bottom: 2px solid #e63946; padding-bottom: 10px; }
                    .section { margin-bottom: 20px; }
                    .info { display: flex; gap: 40px; }
                    .info-col { flex: 1; }
                    .info-col div { margin-bottom: 8px; }
                    .label { font-weight: bold; color: #666; }
                    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    th { background: #f0f0f0; padding: 8px; text-align: left; border: 1px solid #ddd; }
                    td { padding: 8px; border: 1px solid #ddd; }
                    .total-section { font-size: 14px; margin-top: 20px; }
                    .total { font-size: 18px; font-weight: bold; color: #e63946; }
                </style>
            </head>
            <body>
                <h2>DETALLES DE LA VENTA ${this.ventaActual.numero_factura}</h2>

                <div class="section info">
                    <div class="info-col">
                        <div><span class="label">Cliente:</span> ${this.ventaActual.cliente}</div>
                        <div><span class="label">Documento:</span> ${this.ventaActual.cliente_documento || '—'}</div>
                        <div><span class="label">Fecha:</span> ${this.ventaActual.fecha_venta}</div>
                    </div>
                    <div class="info-col">
                        <div><span class="label">Método de Pago:</span> ${this.mapearMetodo(this.ventaActual.metodo_pago)}</div>
                        <div><span class="label">Estado:</span> ${this.ventaActual.estado.toUpperCase()}</div>
                    </div>
                </div>

                <div class="section">
                    <h3>Productos</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${this.ventaActual.detalles.map(d => `
                                <tr>
                                    <td>${d.producto?.nombre}</td>
                                    <td>${d.cantidad}</td>
                                    <td>€${parseFloat(d.precio_unitario).toFixed(2)}</td>
                                    <td>€${parseFloat(d.subtotal).toFixed(2)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>

                <div class="section total-section">
                    <div>SUBTOTAL: €${this.ventaActual.subtotal.toFixed(2)}</div>
                    <div>IMPUESTO (21%): €${this.ventaActual.impuesto.toFixed(2)}</div>
                    <div class="total">TOTAL: €${this.ventaActual.total.toFixed(2)}</div>
                </div>

                ${this.ventaActual.observaciones ? `
                    <div class="section">
                        <h3>Observaciones:</h3>
                        <p>${this.ventaActual.observaciones}</p>
                    </div>
                ` : ''}
            </body>
            </html>
        `;

        ventana.document.write(contenido);
        ventana.document.close();
        setTimeout(() => ventana.print(), 250);
    }
};

// Auto-init cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Inicializando ModalVerVenta...');
    window.ModalVerVenta.init();
});

console.log('✅ modal-ver-venta.js cargado');