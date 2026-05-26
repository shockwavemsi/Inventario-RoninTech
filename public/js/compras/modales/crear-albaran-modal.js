/**
 * Crear Albarán Modal - VERSIÓN FINAL CON LÓGICA CORRECTA
 * - Si pedido tiene albaranes previos → Mostrar SOLO faltantes
 * - Si pedido NO tiene albaranes → Mostrar TODOS
 * - Max input = cantidad_faltante (no se puede superar)
 */

class CrearAlbaranModal {

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
        console.log('✅ Pedidos disponibles:', pedidosData.length);
    }

    static cargarProductosPedido() {
        const pedidoId = document.getElementById('pedidoSelect')?.value;
        console.log('📦 Cargando productos del pedido:', pedidoId);

        if (!pedidoId) {
            console.warn('⚠️ No hay pedido seleccionado');
            return;
        }

        // ✅ ENDPOINT INTELIGENTE - Decide automáticamente qué mostrar
        fetch(`/api/pedidos/${pedidoId}/productos`)
            .then(res => res.json())
            .then(lineas => {
                console.log('📊 Respuesta del servidor:', lineas);

                const tbody = document.getElementById('productosListAlbaran');
                tbody.innerHTML = '';

                if (!lineas || lineas.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: #90ee90; padding: 2rem;">✅ Pedido completamente recibido</td></tr>';
                    return;
                }

                lineas.forEach((linea, index) => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td style="color: #f0f0f0; text-align: center; font-weight: 600;">${index + 1}</td>
                        <td style="color: #f0f0f0;">${linea.producto_nombre || '—'}</td>
                        <td style="color: #f0f0f0; text-align: center;">${linea.cantidad_pedida}</td>
                        <td style="color: #f0f0f0; text-align: center;">
                            <input type="number" 
                                name="cantidad_recibida[]" 
                                value="0" 
                                min="0" 
                                max="${linea.cantidad_faltante}"
                                class="form-control form-control-sm cantidad-recibida" 
                                data-fila="${index + 1}"
                                data-faltante="${linea.cantidad_faltante}"
                                style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; width: 70px; text-align: center;"
                                onchange="window.CrearAlbaranModal.validarCantidad(this)">
                        </td>
                        <td style="color: #fed7aa; text-align: center; font-weight: bold;" class="faltante">${linea.cantidad_faltante}</td>
                        <td style="color: #f0f0f0; text-align: center;">
                            <span class="estado" style="color: #ffc107; font-weight: bold;">PENDIENTE</span>
                        </td>
                        <input type="hidden" name="producto_id[]" value="${linea.producto_id}">
                        <input type="hidden" name="cantidad_pedida[]" value="${linea.cantidad_pedida}">
                        <input type="hidden" name="cantidad_faltante[]" value="${linea.cantidad_faltante}">
                        <input type="hidden" name="estado[]" value="recibido">
                    `;
                    tbody.appendChild(tr);
                });

                console.log(`✅ ${lineas.length} productos cargados`);
            })
            .catch(err => {
                console.error('❌ Error:', err);
                const tbody = document.getElementById('productosListAlbaran');
                tbody.innerHTML = `<tr><td colspan="6" style="text-align: center; color: #e63946; padding: 2rem;">❌ Error al cargar productos</td></tr>`;
            });
    }

    static validarCantidad(input) {
        const cantidadFaltante = parseInt(input.dataset.faltante) || 0;
        const cantidadRecibida = parseInt(input.value) || 0;

        console.log('🔍 Validando:', { cantidadFaltante, cantidadRecibida });

        // ✅ NO DEJAR SUPERAR EL MÁXIMO
        if (cantidadRecibida > cantidadFaltante) {
            alert(`⚠️ No puedes recibir más de ${cantidadFaltante} unidades`);
            input.value = cantidadFaltante;
            return;
        }

        if (cantidadRecibida < 0) {
            alert('⚠️ La cantidad no puede ser negativa');
            input.value = 0;
            return;
        }

        const fila = input.closest('tr');
        const spanFaltante = fila.querySelector('.faltante');
        const spanEstado = fila.querySelector('.estado');
        const inputFaltante = fila.querySelector('input[name="cantidad_faltante[]"]');

        const nuevoFaltante = cantidadFaltante - cantidadRecibida;

        if (spanFaltante) spanFaltante.textContent = nuevoFaltante;
        if (inputFaltante) inputFaltante.value = nuevoFaltante;

        if (nuevoFaltante === 0) {
            if (spanEstado) {
                spanEstado.textContent = 'COMPLETO';
                spanEstado.style.color = '#00ff00';
            }
            fila.querySelector('input[name="estado[]"]').value = 'recibido';
        } else if (cantidadRecibida > 0) {
            if (spanEstado) {
                spanEstado.textContent = 'PARCIAL';
                spanEstado.style.color = '#ffc107';
            }
            fila.querySelector('input[name="estado[]"]').value = 'falta';
        } else {
            if (spanEstado) {
                spanEstado.textContent = 'PENDIENTE';
                spanEstado.style.color = '#ffc107';
            }
            fila.querySelector('input[name="estado[]"]').value = 'pendiente';
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

        console.log('📤 Enviando albarán...', productosValidos.length, 'productos');
        e.target.submit();
    }

    static inicializarBuscador() {
        const buscador = document.getElementById('buscadorPedido');
        const listaPedidos = document.getElementById('listaPedidos');

        if (!buscador || !listaPedidos) {
            console.warn('⚠️ Buscador no encontrado');
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