/**
 * Modal Ver Devolución
 */
window.ModalVerDevolucion = {
    devolucionActual: null,
    modal: null,

    init() {
        this.modal = document.getElementById('modalVerDevolucion') 
            ? new bootstrap.Modal(document.getElementById('modalVerDevolucion'))
            : null;
        console.log('✅ ModalVerDevolucion inicializado');
    },

    async mostrar(devolucionId) {
        console.log('👁️ Abriendo devolución:', devolucionId);

        try {
            const response = await fetch(`/devoluciones/${devolucionId}/json`);
            
            if (!response.ok) throw new Error('Error al obtener devolución');
            
            const devolucion = await response.json();
            console.log('✅ Devolución cargada:', devolucion);

            this.devolucionActual = devolucion;
            this.renderizar(devolucion);
            this.modal.show();

        } catch (error) {
            console.error('❌ Error:', error);
            alert('❌ Error al cargar la devolución');
        }
    },

    renderizar(devolucion) {
        // INFORMACIÓN GENERAL
        document.getElementById('verDevCodigo').textContent = 'DEV-' + String(devolucion.id).padStart(4, '0');
        document.getElementById('verDevCliente').textContent = devolucion.venta?.cliente || '—';
        document.getElementById('verDevFecha').textContent = new Date(devolucion.fecha).toLocaleDateString('es-ES');

        // ESTADO Y TOTALES
        const estadoMap = {
            'completada': { label: 'Completada', color: '#90ee90' },
            'pendiente': { label: 'Pendiente', color: '#ffc107' },
        };

        const estadoObj = estadoMap[devolucion.estado] || { label: devolucion.estado, color: '#a0a0a0' };
        const estatusBadge = document.getElementById('verDevEstado');
        estatusBadge.textContent = estadoObj.label.toUpperCase();
        estatusBadge.style.background = estadoObj.color;
        estatusBadge.style.color = estadoObj.color === '#ffc107' ? 'black' : 'black';

        document.getElementById('verDevMonto').textContent = '€' + parseFloat(devolucion.total_devuelto).toFixed(2);
        document.getElementById('verDevUsuario').textContent = devolucion.usuario?.name || 'User';

        // PRODUCTOS
        this.renderizarProductos(devolucion.detalles || []);

        // MOTIVO
        document.getElementById('verDevMotivo').textContent = devolucion.motivo || '—';
    },

    renderizarProductos(detalles) {
        const tbody = document.getElementById('verDevProductos');
        tbody.innerHTML = '';

        let total = 0;

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
            total += parseFloat(detalle.subtotal);
        });

        document.getElementById('verDevTotal').textContent = '€' + total.toFixed(2);
    },

    imprimir() {
        if (!this.devolucionActual) return;

        const dev = this.devolucionActual;
        const ventana = window.open('', '', 'width=800,height=600');
        
        const contenido = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Devolución DEV-${String(dev.id).padStart(4, '0')}</title>
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
                    .total { font-size: 18px; font-weight: bold; color: #e63946; }
                </style>
            </head>
            <body>
                <h2>DEVOLUCIÓN DEV-${String(dev.id).padStart(4, '0')}</h2>
                
                <div class="section info">
                    <div class="info-col">
                        <div><span class="label">Cliente:</span> ${dev.venta?.cliente || '—'}</div>
                        <div><span class="label">Fecha:</span> ${new Date(dev.fecha).toLocaleDateString('es-ES')}</div>
                        <div><span class="label">Estado:</span> ${dev.estado.toUpperCase()}</div>
                    </div>
                    <div class="info-col">
                        <div><span class="label">Total Devuelto:</span> €${parseFloat(dev.total_devuelto).toFixed(2)}</div>
                        <div><span class="label">Usuario:</span> ${dev.usuario?.name || '—'}</div>
                    </div>
                </div>

                <div class="section">
                    <h3>Productos Devueltos</h3>
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
                            ${dev.detalles.map(d => `
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

                <div class="section">
                    <div class="total">TOTAL DEVUELTO: €${parseFloat(dev.total_devuelto).toFixed(2)}</div>
                </div>

                ${dev.motivo ? `
                    <div class="section">
                        <h3>Motivo:</h3>
                        <p>${dev.motivo}</p>
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

// Auto-init
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Inicializando ModalVerDevolucion...');
    window.ModalVerDevolucion.init();
});

console.log('✅ modal-ver-devolucion.js cargado');
