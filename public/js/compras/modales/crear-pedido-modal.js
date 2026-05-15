/**
 * Crear Pedido Modal - Gestiona tabla dinámica de productos
 * Módulo ES6 para crear nuevos pedidos de compra
 */

class CrearPedidoModal {

    static getSVG(type) {
        const svgs = {
            plus: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>`,
            trash: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>`,
        };
        return svgs[type] || '';
    }

    static init() {
        const modalEl = document.getElementById('modalPedido');
        if (!modalEl) return;

        modalEl.addEventListener('show.bs.modal', () => this.onModalOpen());
        modalEl.addEventListener('hidden.bs.modal', () => this.onModalClose());

        const formEl = document.getElementById('formPedido');
        if (formEl) {
            formEl.addEventListener('submit', (e) => this.onFormSubmit(e));
        }

        const proveedorSelect = document.getElementById('proveedorSelect');
        if (proveedorSelect) {
            proveedorSelect.addEventListener('change', () => this.filtrarProductos());
        }

        const descuentoInput = document.getElementById('descuentoInput');
        if (descuentoInput) {
            descuentoInput.addEventListener('input', () => this.calcularTotales());
        }

        const fechaPedido = document.getElementById('fechaPedido');
        const fechaEntrega = document.getElementById('fechaEntrega');
        if (fechaPedido) {
            fechaPedido.min = this.obtenerFechaHoy();
            fechaPedido.addEventListener('change', () => this.validarFechas());
        }

        if (fechaEntrega) {
            fechaEntrega.addEventListener('change', () => this.validarFechas());
        }
    }

    static obtenerFechaHoy() {
        const hoy = new Date();
        return hoy.toISOString().split('T')[0];
    }

    static validarFechas() {
        const fechaPedido = document.getElementById('fechaPedido')?.value;
        const fechaEntrega = document.getElementById('fechaEntrega')?.value;
        if (!fechaPedido) return;

        if (fechaEntrega && fechaEntrega < fechaPedido) {
            alert('⚠️ La fecha de entrega debe ser posterior o igual a la fecha del pedido');
            document.getElementById('fechaEntrega').value = fechaPedido;
        }
    }

    static mostrar() {
        console.log('📝 Abriendo modal crear pedido...');
        const modal = document.getElementById('modalPedido');
        if (!modal) {
            console.error('❌ modalPedido no encontrado en el DOM');
            return;
        }

        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }

    static onModalOpen() {
        console.log('✅ Modal abierto');
        this.generarNumeroPedido();
        this.inicializarTabla();
        this.calcularTotales();
        this.filtrarProductos();
    }

    static onModalClose() {
        console.log('🔴 Cerrando modal');
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) backdrop.remove();
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
    }

    static generarNumeroPedido() {
        const ultimoId = window.ultimoPedidoId || 0;
        const siguienteId = ultimoId + 1;
        const numeroPedido = `PC-${siguienteId}`;
        const input = document.getElementById('numeroPedido');
        if (input) input.value = numeroPedido;
    }

    static inicializarTabla() {
        const tabla = document.getElementById('productosList');
        if (!tabla) return;
        tabla.innerHTML = '';
        this.agregarFila();
    }

    static agregarFila() {
        const tabla = document.getElementById('productosList');
        if (!tabla) return;

        const numFila = tabla.querySelectorAll('tr').length + 1;
        const fila = document.createElement('tr');
        fila.className = 'fila-producto';
        fila.innerHTML = `
            <td style="padding: 0.5rem; text-align: center; font-weight: 600; color: #a0a0a0;">${numFila}</td>
            <td style="padding: 0.5rem;">
                <select name="producto_id[]" class="form-select form-select-sm producto-select" required onchange="window.CrearPedidoModal.actualizarPrecio(this)">
                    <option value="">Selecciona...</option>
                </select>
            </td>
            <td style="padding: 0.5rem;">
                <input type="number" name="cantidad[]" class="form-control form-control-sm cantidad" value="1" min="1" required onchange="window.CrearPedidoModal.calcularLinea(this)" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3);">
            </td>
            <td style="padding: 0.5rem;">
                <input type="text" name="precio_unitario[]" class="form-control form-control-sm precio-unitario" readonly style="background: rgba(100, 100, 100, 0.3); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;">
            </td>
            <td style="padding: 0.5rem;">
                <input type="text" name="total[]" class="form-control form-control-sm subtotal" readonly style="background: rgba(100, 100, 100, 0.3); border-color: rgba(230, 57, 70, 0.3); color: #90ee90; font-weight: 600;">
            </td>
            <td style="padding: 0.5rem; text-align: center;">
                <button type="button" class="btn btn-sm btn-danger" onclick="window.CrearPedidoModal.eliminarFila(this)">
                    ${this.getSVG('trash')}
                </button>
            </td>
        `;
        tabla.appendChild(fila);
        this.filtrarProductos();
    }

    static eliminarFila(btn) {
        const tabla = document.getElementById('productosList');
        if (!tabla) return;

        if (tabla.querySelectorAll('tr').length > 1) {
            btn.closest('tr').remove();
            this.actualizarNumeros();
            this.calcularTotales();
        } else {
            alert('⚠️ Debe haber al menos un producto');
        }
    }

    static actualizarNumeros() {
        const filas = document.querySelectorAll('.fila-producto');
        filas.forEach((fila, idx) => {
            fila.querySelector('td:first-child').textContent = idx + 1;
        });
    }

    static filtrarProductos() {
    const proveedorId = document.getElementById('proveedorSelect')?.value;

    if (!proveedorId) {
        document.querySelectorAll('.producto-select').forEach(select => {
            select.innerHTML = '<option value="">Selecciona...</option>';
        });
        return;
    }

    // ✅ PETICIÓN SIMPLE
    fetch(`/api/proveedor/${proveedorId}/productos`)
        .then(res => res.json())
        .then(productos => {
            console.log('✅ Productos cargados:', productos.length);

            document.querySelectorAll('.producto-select').forEach(select => {
                const valorActual = select.value;
                select.innerHTML = '<option value="">Selecciona...</option>';

                productos.forEach(p => {
                    const option = document.createElement('option');
                    option.value = p.id;
                    option.textContent = `${p.nombre} (${p.marca}) - ${ parseFloat(p.precio_compra_final).toFixed(2)}€`;
                    option.dataset.precio = p.precio_compra_final;
                    select.appendChild(option);
                });

                if (valorActual) select.value = valorActual;
            });
        })
        .catch(err => console.error('❌ Error:', err));
}

    static actualizarPrecio(select) {
        const fila = select.closest('tr');
        const precioUnitario = select.options[select.selectedIndex].dataset.precio || 0;
        const inputPrecio = fila.querySelector('.precio-unitario');

        if (inputPrecio) {
            inputPrecio.value = parseFloat(precioUnitario).toFixed(2);
        }

        this.calcularLinea(select);
    }

    static calcularLinea(element) {
        const fila = element.closest('tr');
        const cantidad = parseFloat(fila.querySelector('.cantidad').value) || 0;
        const precioUnitario = parseFloat(fila.querySelector('.precio-unitario').value) || 0;
        const subtotal = cantidad * precioUnitario;

        fila.querySelector('.subtotal').value = subtotal.toFixed(2);
        this.calcularTotales();
    }

    static calcularTotales() {
        let subtotalTotal = 0;
        document.querySelectorAll('.fila-producto').forEach(fila => {
            const cantidad = parseFloat(fila.querySelector('.cantidad').value) || 0;
            const precioUnitario = parseFloat(fila.querySelector('.precio-unitario').value) || 0;
            subtotalTotal += cantidad * precioUnitario;
        });

        const descuentoPorcentaje = parseFloat(document.getElementById('descuentoInput')?.value) || 0;
        const descuentoCantidad = (subtotalTotal * descuentoPorcentaje) / 100;
        const totalFinal = subtotalTotal - descuentoCantidad;

        const display = (value) => value.toFixed(2) + '€';

        const subtotalDisplay = document.getElementById('subtotalDisplay');
        if (subtotalDisplay) subtotalDisplay.textContent = display(subtotalTotal);

        const descuentoDisplay = document.getElementById('descuentoDisplay');
        if (descuentoDisplay) {
            descuentoDisplay.textContent = `DESCUENTO(${descuentoPorcentaje.toFixed(2)}%): ( - ${display(descuentoCantidad)})`;
        }

        const totalDisplay = document.getElementById('totalDisplay');
        if (totalDisplay) totalDisplay.textContent = display(totalFinal);

        document.getElementById('subtotal_hidden').value = subtotalTotal.toFixed(2);
        document.getElementById('descuento_porcentaje_hidden').value = descuentoPorcentaje.toFixed(2);
        document.getElementById('descuento_cantidad_hidden').value = descuentoCantidad.toFixed(2);
        document.getElementById('total_hidden').value = totalFinal.toFixed(2);

        console.log('✅ Totales actualizados:', {
            subtotal: subtotalTotal.toFixed(2),
            descuento_porcentaje: descuentoPorcentaje.toFixed(2),
            descuento_cantidad: descuentoCantidad.toFixed(2),
            total: totalFinal.toFixed(2)
        });
    }

    static onFormSubmit(e) {
        e.preventDefault();

        const filasProductos = document.querySelectorAll('.fila-producto');
        if (filasProductos.length === 0) {
            alert('❌ Agrega al menos un producto');
            return;
        }

        let valido = true;
        filasProductos.forEach(fila => {
            if (!fila.querySelector('.producto-select').value) {
                alert('❌ Selecciona un producto en cada fila');
                valido = false;
            }
        });

        if (!valido) return;

        // ✅ RECALCULAR ANTES DE ENVIAR
        let subtotalTotal = 0;
        document.querySelectorAll('.fila-producto').forEach(fila => {
            const cantidad = parseFloat(fila.querySelector('.cantidad').value) || 0;
            const precioUnitario = parseFloat(fila.querySelector('.precio-unitario').value) || 0;
            subtotalTotal += cantidad * precioUnitario;
        });

        const descuentoPorcentaje = parseFloat(document.getElementById('descuentoInput')?.value) || 0;
        const descuentoCantidad = (subtotalTotal * descuentoPorcentaje) / 100;
        const totalFinal = subtotalTotal - descuentoCantidad;

        // ✅ ACTUALIZAR HIDDEN INPUTS JUSTO ANTES DE ENVIAR
        document.getElementById('subtotal_hidden').value = subtotalTotal.toFixed(2);
        document.getElementById('descuento_porcentaje_hidden').value = descuentoPorcentaje.toFixed(2);
        document.getElementById('descuento_cantidad_hidden').value = descuentoCantidad.toFixed(2);
        document.getElementById('total_hidden').value = totalFinal.toFixed(2);

        // ✅ LOG PARA VERIFICAR
        console.log('📤 VALORES A ENVIAR:', {
            subtotal: subtotalTotal.toFixed(2),
            descuento_porcentaje: descuentoPorcentaje.toFixed(2),
            descuento_cantidad: descuentoCantidad.toFixed(2),
            total: totalFinal.toFixed(2)
        });

        // ✅ ENVIAR FORMULARIO TRADICIONAL
        e.target.submit();
    }

    static agregarProductoBtn() {
        this.agregarFila();
    }
}

// Exponer globalmente
window.CrearPedidoModal = CrearPedidoModal;

// Auto-inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    CrearPedidoModal.init();
});

export default CrearPedidoModal;