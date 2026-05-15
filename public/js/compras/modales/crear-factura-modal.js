class CrearFacturaModal {
    static pagoIndex = 0;
    static albaranActual = null;

    static init() {
        const modalEl = document.getElementById('modalFactura');
        if (!modalEl) return;

        modalEl.addEventListener('show.bs.modal', () => this.onModalOpen());
        const formEl = document.getElementById('formFactura');
        if (formEl) {
            formEl.addEventListener('submit', (e) => this.onFormSubmit(e));
        }

        const selectAlbaran = document.getElementById('albaranSelect');
        if (selectAlbaran) {
            selectAlbaran.addEventListener('change', () => this.cargarDetallesAlbaran());
        }
    }

    static mostrar() {
        const modal = document.getElementById('modalFactura');
        if (!modal) return;
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }

    static onModalOpen() {
    console.log('✅ Modal factura abierto');
    this.generarNumeroFactura();
    this.establecerFechas();
    this.inicializarBuscadorAlbaran();  // ✅ AGREGAR ESTA LÍNEA
    this.pagoIndex = 0;
}

static inicializarBuscadorAlbaran() {
    const buscador = document.getElementById('buscadorAlbaran');
    const listaAlbaranes = document.getElementById('listaAlbaranes');

    if (!buscador || !listaAlbaranes) return;

    buscador.addEventListener('input', (e) => {
        const query = e.target.value.trim();

        if (query.length < 2) {
            listaAlbaranes.style.display = 'none';
            return;
        }

        fetch(`/api/buscar-albaranes?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(albaranes => {
                listaAlbaranes.innerHTML = '';

                if (albaranes.length === 0) {
                    listaAlbaranes.innerHTML = '<p style="padding: 0.5rem; color: #a0a0a0;">No hay albaranes</p>';
                    listaAlbaranes.style.display = 'block';
                    return;
                }

                albaranes.forEach(albaran => {
                    const item = document.createElement('div');
                    item.className = 'list-group-item';
                    item.style.cssText = 'padding: 0.5rem; color: #f0f0f0; cursor: pointer; background: rgba(230, 57, 70, 0.1); border-bottom: 1px solid rgba(230, 57, 70, 0.2);';
                    item.textContent = albaran.numero_albaran;

                    item.addEventListener('click', () => {
                        // ✅ GUARDAR OBJETO COMPLETO
                        this.albaranActual = albaran;

                        // Rellenar campos
                        document.getElementById('albaranSelect').value = albaran.id;
                        buscador.value = albaran.numero_albaran;
                        listaAlbaranes.style.display = 'none';

                        // ✅ LLAMAR AL MÉTODO CORRECTO
                        this.cargarDetallesDesdeObjeto(albaran);
                    });

                    listaAlbaranes.appendChild(item);
                });

                listaAlbaranes.style.display = 'block';
            })
            .catch(err => console.error('❌ Error:', err));
    });

    // Cerrar lista al hacer click afuera
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#buscadorAlbaran') && !e.target.closest('#listaAlbaranes')) {
            listaAlbaranes.style.display = 'none';
        }
    });
}

    static obtenerFechaHoy() {
        const hoy = new Date();
        return hoy.toISOString().split('T')[0];
    }

    static formatearFecha(fecha) {
        const [año, mes, día] = fecha.split('-');
        return `${día}/${mes}/${año}`;
    }

    static establecerFechas() {
        const hoy = this.obtenerFechaHoy();
        document.getElementById('fechaFactura').value = hoy;
        document.getElementById('fechaFacturaDisplay').textContent = this.formatearFecha(hoy);

        const vencimiento = new Date();
        vencimiento.setDate(vencimiento.getDate() + 15);
        const fechaVencimiento = vencimiento.toISOString().split('T')[0];

        document.getElementById('fechaVencimiento').value = fechaVencimiento;
        document.getElementById('fechaVencimientoDisplay').textContent = this.formatearFecha(fechaVencimiento);
    }

    static generarNumeroFactura() {
        const ultimoId = window.ultimoFacturaId || 0;
        const numeroFactura = `FAC-COMP-${String(ultimoId + 1).padStart(3, '0')}`;
        document.getElementById('numeroFactura').value = numeroFactura;
    }

    static cargarAlbaranesDisponibles() {
        const albaranesData = window.albaranesData || [];
        const select = document.getElementById('albaranSelect');
        if (!select) return;

        select.innerHTML = '<option value="">-- Selecciona un albarán --</option>';
        albaranesData.forEach(albaran => {
            const option = document.createElement('option');
            option.value = albaran.id;
            option.textContent = `${albaran.numero_albaran} - ${albaran.proveedor}`;
            select.appendChild(option);
        });

        console.log('✅ Albaranes cargados:', albaranesData.length);
    }

    static cargarDetallesAlbaran() {
           console.log('✅ OBJETO ALBARÁN COMPLETO:', albaran);
    console.log('📦 Productos recibidos:', albaran.productos);
    console.log('💳 Formas pago recibidas:', albaran.formas_pago);
        const selectAlbaran = document.getElementById('albaranSelect');
        const albaranId = selectAlbaran?.value;

        if (!albaranId) {
            this.limpiarDetalles();
            return;
        }

        const albaranesData = window.albaranesData || [];
        const albaran = albaranesData.find(a => a.id == albaranId);

        if (!albaran) {
            console.error('❌ Albarán no encontrado:', albaranId);
            return;
        }

        // ✅ GUARDAR ALBARÁN ACTUAL
        this.albaranActual = albaran;

        console.log('✅ Albarán seleccionado:', albaran.numero_albaran);
        console.log('📦 Formas de pago disponibles:', albaran.formas_pago?.length || 0);
        console.log('📦 Productos:', albaran.productos?.length || 0);

        // Llenar detalles
        document.getElementById('detalleProveedor').textContent = albaran.proveedor || '—';
        document.getElementById('detallePedido').textContent = albaran.pedido || '—';
        document.getElementById('detalleAlbaran').textContent = albaran.numero_albaran || '—';
        document.getElementById('detalleTotal').textContent = `${(albaran.total || 0).toFixed(2)}€`;

        // Cargar líneas y limpiar pagos
        this.cargarLineasAlbaran(albaran);
        this.limpiarFormaPagos();
        this.pagoIndex = 0;
    }

    static cargarDetallesDesdeObjeto(albaranData) {
    console.log('✅ Albarán seleccionado:', albaranData.numero_albaran);
    console.log('📦 Productos:', albaranData.productos?.length || 0);
    console.log('💳 Formas de pago:', albaranData.formas_pago?.length || 0);

    this.albaranActual = albaranData;

    // Llenar detalles
    document.getElementById('detalleProveedor').textContent = albaranData.proveedor || '—';
    document.getElementById('detallePedido').textContent = albaranData.pedido || '—';
    document.getElementById('detalleAlbaran').textContent = albaranData.numero_albaran || '—';
    document.getElementById('detalleTotal').textContent = `${(albaranData.total || 0).toFixed(2)}€`;

    // Cargar líneas
    this.cargarLineasAlbaran(albaranData);
    this.limpiarFormaPagos();
    this.pagoIndex = 0;
}

    static cargarLineasAlbaran(albaran) {
        const tbody = document.getElementById('productosFacturaList');
        if (!tbody) return;

        tbody.innerHTML = '';

        if (!albaran.productos || albaran.productos.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 1.5rem; color: #a0a0a0;">Sin productos</td></tr>';
            return;
        }

        albaran.productos.forEach((producto, idx) => {
            const cantidad = parseInt(producto.cantidad_recibida) || 0;
            const estado = cantidad === (parseInt(producto.cantidad_pedida) || 0) ? 'Completo' : 'Parcial';
            const faltante = (parseInt(producto.cantidad_pedida) || 0) - cantidad;

            const tr = document.createElement('tr');
            tr.style.borderBottom = '1px solid rgba(230, 57, 70, 0.1)';
            tr.innerHTML = `
                <td style="padding: 0.75rem; color: #a0a0a0; text-align: center; font-size: 0.875rem;">${idx + 1}</td>
                <td style="padding: 0.75rem; color: #f0f0f0; font-weight: 500;">
                    <strong>${producto.nombre || 'Desconocido'}</strong>
                </td>
                <td style="padding: 0.75rem; color: #f0f0f0; text-align: center;">${parseInt(producto.cantidad_pedida) || 0}</td>
                <td style="padding: 0.75rem; color: #f0f0f0; text-align: center;">${cantidad}</td>
                <td style="padding: 0.75rem; color: #f0f0f0; text-align: center;">${faltante}</td>
                <td style="padding: 0.75rem; color: ${estado === 'Completo' ? '#90ee90' : '#ffc107'}; text-align: center; font-weight: 600;">
                    ${estado}
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // ✅ AGREGAR FILA DE PAGO CON FORMAS DINÁMICAS
    static agregarFilaPago() {
        if (!this.albaranActual) {
            alert('❌ Selecciona un albarán primero');
            return;
        }

        const tbody = document.getElementById('pagosFacturaList');
        if (!tbody) return;

        // Si está vacío, limpiar mensaje
        if (tbody.innerHTML.includes('No hay métodos')) {
            tbody.innerHTML = '';
        }

        const totalFactura = document.getElementById('detalleTotal').textContent.replace('€', '').trim();
        const formasPago = this.albaranActual.formas_pago || [];

        if (formasPago.length === 0) {
            alert('⚠️ No hay formas de pago disponibles para este proveedor');
            return;
        }

        // Construir options dinámicas
        let optionHTML = '<option value="">— Selecciona forma de pago —</option>';
        formasPago.forEach(forma => {
            // Crear value con toda la info (id|referencia|nombre_banco)
            const value = `${forma.relacion_id}|${forma.referencia || ''}|${forma.nombre_banco || ''}|${forma.banco_name || ''}`;
            optionHTML += `<option value="${value}">${forma.label_completo}</option>`;
        });

        const tr = document.createElement('tr');
        tr.style.borderBottom = '1px solid rgba(230, 57, 70, 0.1)';
        tr.innerHTML = `
            <td style="padding: 0.75rem;">
                <select name="forma_pago_id[]" class="form-select form-select-sm forma-pago-select"
                    onchange="window.CrearFacturaModal.actualizarFormaPago(this)"
                    style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;">
                    ${optionHTML}
                </select>
            </td>
            <td style="padding: 0.75rem;">
                <input type="number" name="monto_pago[]" placeholder="0.00" step="0.01" min="0" 
                    class="form-control form-control-sm"
                    style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; text-align: right;"
                    value="${totalFactura}">
            </td>
            <td style="padding: 0.75rem;">
                <input type="date" name="fecha_pago[]" 
                    class="form-control form-control-sm"
                    style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;">
            </td>
            <td style="padding: 0.75rem;">
                <input type="text" name="referencia_pago[]" placeholder="Ej: REF-001" readonly
                    class="form-control form-control-sm referencia-pago"
                    style="background: rgba(100, 100, 100, 0.3); border-color: rgba(230, 57, 70, 0.3); color: #a0a0a0;">
            </td>
            <td style="padding: 0.75rem;">
                <select name="estado_pago[]" class="form-select form-select-sm"
                    style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;">
                    <option value="pendiente">Pendiente</option>
                    <option value="en_transito">En Tránsito</option>
                    <option value="pagado">Pagado</option>
                    <option value="rechazado">Rechazado</option>
                </select>
            </td>
            <td style="padding: 0.75rem; text-align: center;">
                <button type="button" class="btn btn-sm btn-danger" onclick="window.CrearFacturaModal.eliminarFilaPago(this)">
                    ✕
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    // ✅ ACTUALIZAR REFERENCIA Y VALIDAR
    static actualizarFormaPago(selectElement) {
        const valor = selectElement.value;

        if (!valor) {
            selectElement.closest('tr').querySelector('.referencia-pago').value = '';
            return;
        }

        const [relacionId, referencia, nombreBanco, banco] = valor.split('|');
        const tr = selectElement.closest('tr');

        // Rellenar referencia (readonly)
        tr.querySelector('.referencia-pago').value = referencia || '';

        // ✅ VALIDAR NO REPETIR FORMA DE PAGO
        const formasPagoSeleccionadas = Array.from(
            document.querySelectorAll('select[name="forma_pago_id[]"]')
        ).map(s => s.value).filter(v => v);

        // Contar ocurrencias de esta forma
        const ocurrencias = formasPagoSeleccionadas.filter(v => v.split('|')[0] === relacionId).length;

        if (ocurrencias > 1) {
            alert('⚠️ Esa forma de pago ya está usada. Selecciona otra.');
            selectElement.value = '';
            tr.querySelector('.referencia-pago').value = '';
            return;
        }

        console.log('✅ Forma de pago seleccionada:', nombreBanco);
    }

    // ✅ ELIMINAR FILA DE PAGO
    static eliminarFilaPago(btn) {
        const tbody = document.getElementById('pagosFacturaList');
        btn.closest('tr').remove();

        // Si no hay filas, mostrar mensaje
        if (tbody.querySelectorAll('tr').length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 1.5rem; color: #a0a0a0;">No hay métodos de pago agregados</td></tr>';
        }
    }

    static limpiarFormaPagos() {
        const tbody = document.getElementById('pagosFacturaList');
        if (tbody) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 1.5rem; color: #a0a0a0;">No hay métodos de pago agregados</td></tr>';
        }
    }

    static limpiarDetalles() {
        document.getElementById('detalleProveedor').textContent = '—';
        document.getElementById('detallePedido').textContent = '—';
        document.getElementById('detalleAlbaran').textContent = '—';
        document.getElementById('detalleTotal').textContent = '0.00€';
        document.getElementById('productosFacturaList').innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 1.5rem; color: #a0a0a0;">Selecciona un albarán</td></tr>';
        this.limpiarFormaPagos();
        this.albaranActual = null;
    }

    static onFormSubmit(e) {
    e.preventDefault();

    const numeroFactura = document.getElementById('numeroFactura')?.value;
    const albaranId = document.getElementById('albaranSelect')?.value;

    // ✅ CAMBIO: select, NO input
    const pagos = Array.from(document.querySelectorAll('select[name="forma_pago_id[]"]'))
        .filter(s => s.value); // Solo contar los que tienen valor

    if (!numeroFactura || !albaranId) {
        alert('❌ Error: Faltan datos básicos');
        return;
    }

    if (pagos.length === 0) {
        alert('❌ Error: Debes agregar al menos un método de pago');
        return;
    }

    console.log('📤 Enviando factura con', pagos.length, 'método(s) de pago...');

    e.target.submit();
}
}

window.CrearFacturaModal = CrearFacturaModal;
document.addEventListener('DOMContentLoaded', () => CrearFacturaModal.init());
export default CrearFacturaModal;