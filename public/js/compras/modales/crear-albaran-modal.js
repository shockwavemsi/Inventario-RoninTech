/**
 * Crear Albarán Modal - Gestiona tabla dinámica de productos
 * Módulo ES6 para crear nuevos albaranes de compra
 */

class CrearAlbaranModal {

    static getSVG(type) {
        const svgs = {
            trash: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>`,
        };
        return svgs[type] || '';
    }

    static init() {
        const modalEl = document.getElementById('modalAlbaran');
        if (!modalEl) {
            console.warn('⚠️ modalAlbaran no encontrado');
            return;
        }

        modalEl.addEventListener('show.bs.modal', () => this.onModalOpen());
        modalEl.addEventListener('hidden.bs.modal', () => this.onModalClose());

        const formEl = document.getElementById('formAlbaran');
        if (formEl) {
            formEl.addEventListener('submit', (e) => this.onFormSubmit(e));
        }
    }

    static obtenerFechaHoy() {
        const hoy = new Date();
        return hoy.toISOString().split('T')[0];
    }

    static validarFechas() {
        const fechaRecepcion = document.getElementById('fechaRecepcion')?.value;
        const hoy = this.obtenerFechaHoy();
        if (fechaRecepcion && fechaRecepcion < hoy) {
            alert('⚠️ La fecha de recepción no puede ser anterior a hoy');
            document.getElementById('fechaRecepcion').value = hoy;
        }
    }

    static mostrar() {
        console.log('📦 Abriendo modal crear albarán...');
        const modal = document.getElementById('modalAlbaran');
        if (!modal) {
            console.error('❌ modalAlbaran no encontrado en el DOM');
            return;
        }

        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }

    static onModalOpen() {
        console.log('✅ Modal albarán abierto');
        this.generarNumeroAlbaran();
        this.inicializarTabla();
        this.cargarPedidosDisponibles();
        this.inicializarBuscador();
    }

    static onModalClose() {
        console.log('🔴 Cerrando modal albarán');
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) backdrop.remove();
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
    }

    static generarNumeroAlbaran() {
        const ultimoId = window.ultimoAlbaranId || 0;
        const siguienteId = ultimoId + 1;
        const numeroAlbaran = `ALB-COMP-${siguienteId}`;
        const input = document.getElementById('numeroAlbaran');
        if (input) input.value = numeroAlbaran;
    }

    static cargarPedidosDisponibles() {
        const pedidosData = window.pedidosData || [];
        const pedidoSelectInput = document.getElementById('pedidoSelect');

        if (!pedidoSelectInput) {
            console.error('❌ No se encuentra el input #pedidoSelect en el formulario');
            return;
        }

        console.log('✅ Input #pedidoSelect encontrado');

        const buscador = document.getElementById('buscadorPedido');
        if (!buscador) {
            console.error('❌ No se encuentra el buscador de pedidos');
            return;
        }

        console.log('✅ Buscador de pedidos disponible, datos:', pedidosData.length, 'pedidos');
    }

    static cargarProductosPedido() {
        const pedidoId = document.getElementById('pedidoSelect')?.value;

        console.log('📦 Cargando productos del pedido:', pedidoId);

        if (!pedidoId) {
            console.warn('⚠️ No hay pedido seleccionado');
            return;
        }

        const pedidosData = window.pedidosData || [];
        const pedido = pedidosData.find(p => p.id == pedidoId);

        if (!pedido) {
            console.error('❌ Pedido no encontrado:', pedidoId);
            return;
        }

        const tbody = document.getElementById('productosListAlbaran');
        if (!tbody) {
            console.error('❌ No se encuentra #productosListAlbaran');
            return;
        }

        tbody.innerHTML = '';

        if (!pedido.lineas || pedido.lineas.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: #a0a0a0;">Sin productos en este pedido</td></tr>';
            return;
        }

        let fila_num = 0;
        pedido.lineas.forEach((linea, index) => {
            fila_num++;
            const cantidadPedida = parseInt(linea.cantidad) || 0;
            const nombreProducto = linea.producto?.nombre || linea.producto_nombre || 'Producto desconocido';

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="color: #f0f0f0;">${nombreProducto}</td>
                <td style="color: #f0f0f0; text-align: center;">${cantidadPedida}</td>
                <td style="color: #f0f0f0; text-align: center;">
                    <input type="number" 
                        name="cantidad_recibida[]" 
                        value="${cantidadPedida}" 
                        min="0" 
                        max="${cantidadPedida}"
                        class="form-control form-control-sm cantidad-recibida" 
                        data-fila="${fila_num}"
                        data-pedida="${cantidadPedida}"
                        style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; width: 70px; text-align: center;"
                        onchange="window.CrearAlbaranModal.validarCantidad(this)">
                </td>
                <td style="color: #f0f0f0; text-align: center;">
                    <span class="faltante" data-faltante="0">0</span>
                </td>
                <td style="color: #f0f0f0; text-align: center;">
                    <span class="estado" style="color: #00ff00; font-weight: bold;">Completo</span>
                </td>
                <td style="text-align: center;">
                    <input type="hidden" name="producto_id[]" value="${linea.producto_id || linea.id}">
                    <input type="hidden" name="cantidad_pedida[]" value="${cantidadPedida}">
                    <input type="hidden" name="cantidad_faltante[]" value="0">
                    <input type="hidden" name="estado[]" value="recibido">
                </td>
            `;
            tbody.appendChild(tr);
        });

        console.log('✅ Productos cargados:', pedido.lineas.length);
    }

    static validarCantidad(input) {
        const cantidadPedida = parseInt(input.dataset.pedida) || 0;
        const cantidadRecibida = parseInt(input.value) || 0;

        if (cantidadRecibida > cantidadPedida) {
            alert(`⚠️ No puedes recibir más de ${cantidadPedida} unidades`);
            input.value = cantidadPedida;
            return;
        }

        if (cantidadRecibida < 0) {
            alert('⚠️ La cantidad no puede ser negativa');
            input.value = 0;
            return;
        }

        const faltante = cantidadPedida - cantidadRecibida;
        const fila = input.closest('tr');
        const spanFaltante = fila.querySelector('.faltante');
        const spanEstado = fila.querySelector('.estado');
        const inputFaltante = fila.querySelector('input[name="cantidad_faltante[]"]');

        spanFaltante.textContent = faltante;
        inputFaltante.value = faltante;

        if (faltante === 0) {
            spanEstado.textContent = 'Completo';
            spanEstado.style.color = '#00ff00';
            fila.querySelector('input[name="estado[]"]').value = 'recibido';
        } else {
            spanEstado.textContent = 'Parcial';
            spanEstado.style.color = '#ffc107';
            fila.querySelector('input[name="estado[]"]').value = 'falta';
        }
    }

    static inicializarTabla() {
        const tbody = document.getElementById('productosListAlbaran');
        if (tbody) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: #a0a0a0;">Selecciona un pedido para cargar productos</td></tr>';
        }
    }

    static onFormSubmit(e) {
        e.preventDefault();

        const numeroAlbaran = document.getElementById('numeroAlbaran')?.value;
        const pedidoId = document.getElementById('pedidoSelect')?.value;
        const fechaAlbaran = document.querySelector('input[name="fecha_albaran"]')?.value;
        const fechaRecepcion = document.querySelector('input[name="fecha_recepcion"]')?.value;
        const observaciones = document.querySelector('textarea[name="observaciones"]')?.value || '';

        if (!numeroAlbaran) {
            alert('❌ Faltan datos: Número de albarán');
            return;
        }

        if (!pedidoId) {
            alert('❌ Faltan datos: Selecciona un pedido');
            return;
        }

        const tbody = document.getElementById('productosListAlbaran');
        const filas = tbody.querySelectorAll('tr');

        if (filas.length === 0 || (filas.length === 1 && filas[0].textContent.includes('Selecciona'))) {
            alert('❌ Agrega productos antes de guardar');
            return;
        }

        const productosValidos = Array.from(filas).filter(fila => {
            return fila.querySelector('input[name="producto_id[]"]') !== null;
        });

        if (productosValidos.length === 0) {
            alert('❌ Debe haber al menos un producto');
            return;
        }

        console.log('📤 Enviando albarán:', {
            numero_albaran: numeroAlbaran,
            pedido_compra_id: pedidoId,
            fecha_albaran: fechaAlbaran,
            fecha_recepcion: fechaRecepcion,
            observaciones: observaciones,
            productos: productosValidos.length
        });

        e.target.submit();
    }

    static inicializarBuscador() {
        const buscador = document.getElementById('buscadorPedido');
        const listaPedidos = document.getElementById('listaPedidos');

        if (!buscador || !listaPedidos) {
            console.warn('⚠️ Buscador o listaPedidos no encontrados');
            return;
        }

        buscador.addEventListener('input', (e) => {
            const query = e.target.value.trim();

            if (query.length < 2) {
                listaPedidos.style.display = 'none';
                return;
            }

            fetch(`/api/buscar-pedidos?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(pedidos => {
                    listaPedidos.innerHTML = '';

                    if (pedidos.length === 0) {
                        listaPedidos.innerHTML = '<p style="padding: 0.5rem; color: #a0a0a0;">No hay pedidos</p>';
                        listaPedidos.style.display = 'block';
                        return;
                    }

                    pedidos.forEach(pedido => {
                        const item = document.createElement('div');
                        item.className = 'list-group-item';
                        item.style.cssText = 'padding: 0.5rem; color: #f0f0f0; cursor: pointer; background: rgba(230, 57, 70, 0.1); border-bottom: 1px solid rgba(230, 57, 70, 0.2);';
                        item.textContent = pedido.numero_pedido;

                        item.addEventListener('click', () => {
                            document.getElementById('pedidoSelect').value = pedido.id;
                            buscador.value = pedido.numero_pedido;
                            listaPedidos.style.display = 'none';
                            window.CrearAlbaranModal.cargarProductosPedido();
                        });

                        listaPedidos.appendChild(item);
                    });

                    listaPedidos.style.display = 'block';
                })
                .catch(err => console.error('❌ Error:', err));
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('#buscadorPedido') && !e.target.closest('#listaPedidos')) {
                listaPedidos.style.display = 'none';
            }
        });
    }
}

window.CrearAlbaranModal = CrearAlbaranModal;

document.addEventListener('DOMContentLoaded', () => {
    CrearAlbaranModal.init();
});

export default CrearAlbaranModal;