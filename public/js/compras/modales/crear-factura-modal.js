class CrearFacturaModal {
    static albaranActual = null;
    static totalAlbaran = 0;

    static init() {
        console.log('✅ Inicializando CrearFacturaModal');
        const formEl = document.getElementById('formFactura');
        if (formEl) {
            formEl.addEventListener('submit', (e) => this.onFormSubmit(e));
        }

        const modalEl = document.getElementById('modalFactura');
        if (modalEl) {
            modalEl.addEventListener('show.bs.modal', () => this.onModalOpen());
        }
    }

    static mostrar() {
        const modal = new bootstrap.Modal(document.getElementById('modalFactura'));
        modal.show();
    }

    static onModalOpen() {
        console.log('✅ Modal factura abierto');
        this.generarNumeroFactura();
        this.establecerFechas();
        this.inicializarBuscadorAlbaran();
        this.limpiarDetalles();
    }

    static generarNumeroFactura() {
        const ultimoId = window.ultimoFacturaId || 0;
        const numeroFactura = `FAC-COMP-${String(ultimoId + 1).padStart(4, '0')}`;
        document.getElementById('numeroFactura').value = numeroFactura;
        console.log('📝 Número generado:', numeroFactura);
    }

    static establecerFechas() {
        const hoy = new Date().toISOString().split('T')[0];

        // Mostrar en los display divs
        document.getElementById('fechaFacturaDisplay').textContent = hoy;

        // Guardar en inputs hidden
        document.getElementById('fechaFactura').value = hoy;

        console.log('📅 Fecha factura establecida:', hoy);
    }

    static inicializarBuscadorAlbaran() {
        const buscador = document.getElementById('buscadorAlbaran');
        const listaAlbaranes = document.getElementById('listaAlbaranes');

        if (!buscador || !listaAlbaranes) {
            console.error('❌ No se encontraron elementos del buscador');
            return;
        }

        buscador.addEventListener('input', (e) => {
            const query = e.target.value.trim();

            if (query.length < 2) {
                listaAlbaranes.style.display = 'none';
                return;
            }

            console.log('🔍 Buscando albaranes con:', query);

            fetch(`/compras/api/buscar-albaranes?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(albaranes => {
                    console.log('📦 Albaranes encontrados:', albaranes.length);
                    listaAlbaranes.innerHTML = '';

                    if (albaranes.length === 0) {
                        listaAlbaranes.innerHTML = '<div style="padding: 0.5rem; color: #a0a0a0;">No hay albaranes</div>';
                        listaAlbaranes.style.display = 'block';
                        return;
                    }

                    albaranes.forEach(albaran => {
                        const item = document.createElement('div');
                        item.className = 'list-group-item';
                        item.style.cssText = 'padding: 0.75rem; color: #f0f0f0; cursor: pointer; background: rgba(230, 57, 70, 0.1); border-bottom: 1px solid rgba(230, 57, 70, 0.2);';
                        item.innerHTML = `
    <div style="font-weight: 600; color: #e63946;">${albaran.numero_albaran}</div>
    <div style="font-size: 0.8rem; color: #a0a0a0;">${albaran.proveedor} - €${(parseFloat(albaran.total) || 0).toFixed(2)}</div>
`;

                        item.addEventListener('click', () => {
                            document.getElementById('albaranSelect').value = albaran.id;
                            buscador.value = albaran.numero_albaran;
                            listaAlbaranes.style.display = 'none';
                            this.cargarAlbaran(albaran.id);
                        });

                        listaAlbaranes.appendChild(item);
                    });

                    listaAlbaranes.style.display = 'block';
                })
                .catch(err => {
                    console.error('❌ Error al buscar:', err);
                    alert('Error al buscar albaranes');
                });
        });

        // Cerrar al hacer click afuera
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#buscadorAlbaran') && !e.target.closest('#listaAlbaranes')) {
                listaAlbaranes.style.display = 'none';
            }
        });
    }

    static cargarAlbaran(albaranId) {
        if (!albaranId) return;

        console.log('📦 Cargando albarán:', albaranId);

        fetch(`/compras/albaranes/${albaranId}/json`)
            .then(res => res.json())
            .then(albaran => {
                console.log('✅ Albarán cargado:', albaran);
                this.albaranActual = albaran;
                this.totalAlbaran = parseFloat(albaran.total) || 0;

                // Llenar detalles
                document.getElementById('detalleProveedor').textContent = albaran.proveedor || '—';
                document.getElementById('detallePedido').textContent = albaran.numero_pedido || '—';
                document.getElementById('detalleAlbaran').textContent = albaran.numero_albaran || '—';
                document.getElementById('detalleTotal').textContent = `€${(parseFloat(albaran.total) || 0).toFixed(2)}`;

                // Cargar líneas
                this.cargarLineasAlbaran(albaran);

                // Cargar formas de pago
                this.cargarFormasPago(albaran.proveedor_id);

                // ✅ OBTENER DÍAS DE VENCIMIENTO Y CALCULAR FECHA
                this.cargarDiasVencimiento(albaran.proveedor_id);

                console.log('✅ Albarán cargado completamente');
            })
            .catch(err => {
                console.error('❌ Error:', err);
                alert('Error al cargar albarán');
            });
    }

    static cargarLineasAlbaran(albaran) {
        const tbody = document.getElementById('productosFacturaList');
        if (!tbody) return;

        tbody.innerHTML = '';

        if (!albaran.detalles || albaran.detalles.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 1.5rem; color: #a0a0a0;">📭 Sin productos recibidos</td></tr>';
            return;
        }

        console.log('📦 Cargando', albaran.detalles.length, 'productos');

        albaran.detalles.forEach((producto, idx) => {
            const cantidad = producto.cantidad_recibida;

            const tr = document.createElement('tr');
            tr.style.borderBottom = '1px solid rgba(230, 57, 70, 0.1)';
            tr.innerHTML = `
                <td style="padding: 0.75rem; color: #a0a0a0; text-align: center; font-size: 0.875rem;">${idx + 1}</td>
                <td style="padding: 0.75rem; color: #f0f0f0; font-weight: 500;">${producto.producto_nombre}</td>
                <td style="padding: 0.75rem; color: #f0f0f0; text-align: center;">${producto.cantidad_pedida}</td>
                <td style="padding: 0.75rem; color: #f0f0f0; text-align: center;">${cantidad}</td>
            `;
            tbody.appendChild(tr);
        });
    }

    static cargarFormasPago(proveedorId) {
        const tbody = document.getElementById('pagosFacturaList');
        if (!tbody) return;

        console.log('💳 Cargando formas para proveedor:', proveedorId);

        fetch(`/compras/api/proveedor/${proveedorId}/formas-pago`)
            .then(res => res.json())
            .then(formas => {
                console.log('💳 Formas recibidas:', formas.length);
                tbody.innerHTML = '';

                if (!formas || formas.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 1.5rem; color: #a0a0a0;">⚠️ Sin formas de pago</td></tr>';
                    return;
                }

                formas.forEach((forma, idx) => {
                    console.log('➕ Agregando forma:', forma.forma_pago);
                    const tr = document.createElement('tr');
                    tr.style.borderBottom = '1px solid rgba(230, 57, 70, 0.1)';
                    tr.innerHTML = `
                        <td style="padding: 0.75rem;">
                            <select name="forma_pago_id[]" class="form-select form-select-sm" 
                                style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;">
                                <option value="${forma.id}" selected>${forma.forma_pago}</option>
                            </select>
                        </td>
                        <td style="padding: 0.75rem;">
                            <input type="number" name="monto_pago[]" placeholder="0.00" step="0.01" min="0" 
                                class="form-control form-control-sm monto-input"
                                style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; text-align: right;"
                                value="0.00"
                                onchange="window.CrearFacturaModal.validarMonto(this)">
                        </td>
                        <td style="padding: 0.75rem;">
                            <input type="date" name="fecha_pago[]" 
                                class="form-control form-control-sm"
                                style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;">
                        </td>
                        <td style="padding: 0.75rem;">
                            <input type="text" name="referencia_pago[]" placeholder="Referencia" readonly
                                class="form-control form-control-sm"
                                style="background: rgba(100, 100, 100, 0.3); border-color: rgba(230, 57, 70, 0.3); color: #a0a0a0;"
                                value="${forma.referencia || ''}">
                        </td>
                        <td style="padding: 0.75rem;">
                            <select name="estado_pago[]" class="form-select form-select-sm"
                                style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;">
                                <option value="pendiente">Pendiente</option>
                                <option value="en_transito">En Tránsito</option>
                                <option value="pagado">Pagado</option>
                            </select>
                        </td>
                        <td style="padding: 0.75rem; text-align: center;">
                            <button type="button" class="btn btn-sm btn-danger" onclick="window.CrearFacturaModal.eliminarFilaPago(this)">✕</button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                console.log('✅ Formas de pago cargadas:', formas.length);
            })
            .catch(err => {
                console.error('❌ Error:', err);
                tbody.innerHTML = `<tr><td colspan="6" style="text-align: center; color: red;">Error: ${err.message}</td></tr>`;
            });
    }

    static cargarDiasVencimiento(proveedorId) {
        fetch(`/compras/api/proveedor/${proveedorId}/dias-vencimiento`)
            .then(res => res.json())
            .then(data => {
                const hoy = new Date();
                const dias = data.dias || 30;
                const vencimiento = new Date(hoy.getTime() + dias * 24 * 60 * 60 * 1000);

                const fechaVencimiento = vencimiento.toISOString().split('T')[0];

                // Mostrar en display div
                document.getElementById('fechaVencimientoDisplay').textContent = fechaVencimiento;

                // Guardar en input hidden
                document.getElementById('fechaVencimiento').value = fechaVencimiento;

                console.log(`✅ Vencimiento en ${dias} días:`, fechaVencimiento);
            })
            .catch(err => console.error('❌ Error vencimiento:', err));
    }

    static agregarFilaPago() {
        if (!this.albaranActual) {
            alert('❌ Selecciona un albarán primero');
            return;
        }

        const tbody = document.getElementById('pagosFacturaList');
        if (!tbody) return;

        if (tbody.innerHTML.includes('Sin formas')) {
            tbody.innerHTML = '';
        }

        const tr = document.createElement('tr');
        tr.style.borderBottom = '1px solid rgba(230, 57, 70, 0.1)';
        tr.innerHTML = `
            <td style="padding: 0.75rem;">
                <select name="forma_pago_id[]" class="form-select form-select-sm" 
                    style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;">
                    <option value="">— Selecciona forma de pago —</option>
                </select>
            </td>
            <td style="padding: 0.75rem;">
                <input type="number" name="monto_pago[]" placeholder="0.00" step="0.01" min="0" 
                    class="form-control form-control-sm monto-input"
                    style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; text-align: right;"
                    onchange="window.CrearFacturaModal.validarMonto(this)">
            </td>
            <td style="padding: 0.75rem;">
                <input type="date" name="fecha_pago[]" 
                    class="form-control form-control-sm"
                    style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;">
            </td>
            <td style="padding: 0.75rem;">
                <input type="text" name="referencia_pago[]" placeholder="Referencia" readonly
                    class="form-control form-control-sm"
                    style="background: rgba(100, 100, 100, 0.3); border-color: rgba(230, 57, 70, 0.3); color: #a0a0a0;">
            </td>
            <td style="padding: 0.75rem;">
                <select name="estado_pago[]" class="form-select form-select-sm"
                    style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;">
                    <option value="pendiente">Pendiente</option>
                    <option value="en_transito">En Tránsito</option>
                    <option value="pagado">Pagado</option>
                </select>
            </td>
            <td style="padding: 0.75rem; text-align: center;">
                <button type="button" class="btn btn-sm btn-danger" onclick="window.CrearFacturaModal.eliminarFilaPago(this)">✕</button>
            </td>
        `;
        tbody.appendChild(tr);

        console.log('✅ Fila de pago agregada');
    }

    static eliminarFilaPago(btn) {
        btn.closest('tr').remove();
        this.validarTotalPagos();
        console.log('✅ Fila eliminada');
    }

    static validarMonto(input) {
        const monto = parseFloat(input.value) || 0;
        const totalAlbaran = this.totalAlbaran || 0;

        console.log(`💰 Validando: monto=${monto}, total=${totalAlbaran}`);

        if (monto > totalAlbaran) {
            alert(`❌ El monto (€${monto.toFixed(2)}) SUPERA el total del albarán (€${totalAlbaran.toFixed(2)})\n\nMonto máximo permitido: €${totalAlbaran.toFixed(2)}`);
            input.value = totalAlbaran.toFixed(2);
            input.style.borderColor = '#e63946';
            input.style.backgroundColor = 'rgba(230, 57, 70, 0.2)';
        } else {
            input.style.borderColor = 'rgba(230, 57, 70, 0.3)';
            input.style.backgroundColor = 'rgba(20, 20, 25, 0.8)';
        }

        // ✅ VALIDAR QUE TOTAL DE PAGOS NO SUPERE ALBARÁN
        this.validarTotalPagos();
    }

    static validarTotalPagos() {
        const inputs = document.querySelectorAll('input[name="monto_pago[]"]');
        let totalPagos = 0;

        inputs.forEach(input => {
            totalPagos += parseFloat(input.value) || 0;
        });

        const totalAlbaran = this.totalAlbaran || 0;
        console.log(`📊 Total pagos: €${totalPagos.toFixed(2)}, Total albarán: €${totalAlbaran.toFixed(2)}`);

        if (totalPagos > totalAlbaran) {
            alert(`❌ ⚠️ ADVERTENCIA: La suma de todos los pagos (€${totalPagos.toFixed(2)}) SUPERA el total (€${totalAlbaran.toFixed(2)})`);
        }
    }

    static limpiarDetalles() {
        document.getElementById('detalleProveedor').textContent = '—';
        document.getElementById('detallePedido').textContent = '—';
        document.getElementById('detalleAlbaran').textContent = '—';
        document.getElementById('detalleTotal').textContent = '€0.00';

        const productosTable = document.getElementById('productosFacturaList');
        if (productosTable) {
            productosTable.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 1.5rem; color: #a0a0a0;">Selecciona un albarán primero</td></tr>';
        }

        const pagosTable = document.getElementById('pagosFacturaList');
        if (pagosTable) {
            pagosTable.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 1.5rem; color: #a0a0a0;">No hay métodos de pago agregados</td></tr>';
        }

        this.albaranActual = null;
        this.totalAlbaran = 0;
    }

    static onFormSubmit(e) {
        e.preventDefault();

        const numeroFactura = document.getElementById('numeroFactura')?.value;
        const albaranId = document.getElementById('albaranSelect')?.value;
        const fechaFactura = document.getElementById('fechaFactura')?.value;

        console.log('📤 Validando factura:', { numeroFactura, albaranId, fechaFactura });

        if (!numeroFactura || !albaranId || !fechaFactura) {
            alert('❌ Faltan datos obligatorios');
            return;
        }

        const pagos = Array.from(document.querySelectorAll('input[name="monto_pago[]"]'));
        if (pagos.length === 0) {
            alert('❌ Debe haber al menos un método de pago');
            return;
        }

        console.log('✅ Validación OK, enviando...');
        e.target.submit();
    }
}

window.CrearFacturaModal = CrearFacturaModal;
document.addEventListener('DOMContentLoaded', () => CrearFacturaModal.init());
export default CrearFacturaModal;