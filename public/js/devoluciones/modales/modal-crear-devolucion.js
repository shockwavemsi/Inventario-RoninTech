/**
 * Modal Crear Devolución - Búsqueda de Ventas
 */
window.ModalCrearDevolucion = {
    ventaActual: null,
    productosSeleccionados: {},
    modal: null,
    todasLasVentas: [],

    init() {
        this.modal = document.getElementById('modalCrearDevolucion') 
            ? new bootstrap.Modal(document.getElementById('modalCrearDevolucion'))
            : null;

        this.cargarVentas();
        this.inicializarEventos();
        console.log('✅ ModalCrearDevolucion inicializado');
    },

    async cargarVentas() {
        try {
            const response = await fetch('/devoluciones/ventas-disponibles');
            if (!response.ok) throw new Error('Error al cargar ventas');

            const data = await response.json();
            this.todasLasVentas = data.ventas || [];
            console.log('✅ Ventas cargadas:', this.todasLasVentas);
        } catch (error) {
            console.error('❌ Error cargando ventas:', error);
        }
    },

    inicializarEventos() {
        const buscador = document.getElementById('crearDevBuscadorVenta');
        const lista = document.getElementById('crearDevListaVentas');

        if (!buscador || !lista) return;

        buscador.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();

            if (query.length < 1) {
                lista.style.display = 'none';
                return;
            }

            this.filtrarVentas(query, lista);
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('#crearDevBuscadorVenta') && !e.target.closest('#crearDevListaVentas')) {
                lista.style.display = 'none';
            }
        });

        const form = document.getElementById('formDevolucion');
        if (form) {
            form.addEventListener('submit', (e) => this.onFormSubmit(e));
        }
    },

    filtrarVentas(query, container) {
        container.innerHTML = '';

        const resultados = this.todasLasVentas.filter(venta => {
            const numero = venta.numero_factura.toLowerCase();
            const cliente = (venta.cliente || '').toLowerCase();
            const monto = venta.total.toString();

            return numero.includes(query) || cliente.includes(query) || monto.includes(query);
        });

        if (resultados.length === 0) {
            container.innerHTML = '<li class="list-group-item" style="padding: 0.5rem; color: #a0a0a0;">No hay ventas</li>';
            container.style.display = 'block';
            return;
        }

        resultados.forEach(venta => {
            const item = document.createElement('li');
            item.className = 'list-group-item';
            item.style.cssText = 'padding: 0.75rem; color: #f0f0f0; cursor: pointer; background: rgba(230, 57, 70, 0.1); border-bottom: 1px solid rgba(230, 57, 70, 0.2); font-size: 0.9rem;';
            item.innerHTML = `
                <strong>${venta.numero_factura}</strong> - ${venta.cliente}
                <br><small style="color: #a0a0a0;">€${parseFloat(venta.total).toFixed(2)} | ${venta.fecha}</small>
            `;

            item.addEventListener('click', () => this.seleccionarVenta(venta));

            container.appendChild(item);
        });

        container.style.display = 'block';
    },

    async seleccionarVenta(venta) {
        console.log('📋 Venta seleccionada:', venta);

        document.getElementById('crearDevBuscadorVenta').value = `${venta.numero_factura} - ${venta.cliente}`;
        document.getElementById('crearDevVentaId').value = venta.id;
        document.getElementById('crearDevCliente').value = venta.cliente || '';
        document.getElementById('crearDevListaVentas').style.display = 'none';

        try {
            const response = await fetch(`/ventas/${venta.id}/json`);
            if (!response.ok) throw new Error('Error al obtener venta');

            const ventaData = await response.json();
            this.ventaActual = ventaData;
            this.renderizarProductos(ventaData.detalles || []);

        } catch (error) {
            console.error('❌ Error:', error);
            alert('❌ Error al cargar los detalles de la venta');
        }
    },

    renderizarProductos(detalles) {
        const tbody = document.getElementById('crearDevProductos');
        tbody.innerHTML = '';
        this.productosSeleccionados = {};

        if (detalles.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 2rem; color: #a0a0a0;">Sin productos en esta venta</td></tr>';
            return;
        }

        detalles.forEach((detalle, idx) => {
            const tr = document.createElement('tr');
            const productoId = detalle.producto?.id || detalle.producto_id;

            tr.innerHTML = `
                <td style="text-align: center;">
                    <input type="checkbox" class="checked-devolver" data-idx="${idx}" data-product-id="${productoId}" onchange="window.ModalCrearDevolucion.calcularTotales()">
                </td>
                <td>${detalle.producto?.nombre || 'Producto'}</td>
                <td style="text-align: center;">${detalle.cantidad}</td>
                <td style="text-align: center;">
                    <input type="number" class="form-control form-control-sm cantidad-devolver" min="0" max="${detalle.cantidad}" value="0" data-idx="${idx}" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; text-align: center; width: 70px;" onchange="window.ModalCrearDevolucion.calcularTotales()">
                </td>
                <td style="text-align: right;">€${parseFloat(detalle.precio_unitario).toFixed(2)}</td>
                <td style="text-align: right; color: #e63946; font-weight: 600;">
                    <span class="subtotal-linea" data-idx="${idx}">€0.00</span>
                </td>
            `;
            tbody.appendChild(tr);
        });

        this.calcularTotales();
    },

    calcularTotales() {
        const checkboxes = document.querySelectorAll('.checked-devolver');
        this.productosSeleccionados = {};
        let subtotal = 0;

        checkboxes.forEach((checkbox, idx) => {
            if (checkbox.checked) {
                const cantidadInput = document.querySelector(`.cantidad-devolver[data-idx="${idx}"]`);
                const cantidadElegida = parseInt(cantidadInput?.value) || 0;

                if (cantidadElegida > 0) {
                    const productoId = checkbox.dataset.productId;
                    const detalleOriginal = this.ventaActual.detalles[idx];
                    const precioUnitario = parseFloat(detalleOriginal.precio_unitario);
                    const subtotalLinea = cantidadElegida * precioUnitario;

                    this.productosSeleccionados[idx] = {
                        producto_id: productoId,
                        cantidad: cantidadElegida,
                        precio_unitario: precioUnitario,
                        subtotal: subtotalLinea
                    };

                    subtotal += subtotalLinea;

                    document.querySelector(`.subtotal-linea[data-idx="${idx}"]`).textContent = 
                        '€' + subtotalLinea.toFixed(2);
                } else {
                    document.querySelector(`.subtotal-linea[data-idx="${idx}"]`).textContent = '€0.00';
                }
            } else {
                document.querySelector(`.subtotal-linea[data-idx="${idx}"]`).textContent = '€0.00';
            }
        });

        const iva = subtotal * 0.21;
        const total = subtotal + iva;

        document.getElementById('crearDevSubtotal').textContent = '€' + subtotal.toFixed(2);
        document.getElementById('crearDevIva').textContent = '€' + iva.toFixed(2);
        document.getElementById('crearDevTotalModal').textContent = '€' + total.toFixed(2);
        document.getElementById('crearDevTotalFooter').textContent = '€' + total.toFixed(2);

        document.getElementById('crearDevProductosJson').value = JSON.stringify(Object.values(this.productosSeleccionados));
        document.getElementById('crearDevTotalInput').value = total.toFixed(2);
    },

    onFormSubmit(event) {
        console.log('📤 Enviando devolución...');

        if (!document.getElementById('crearDevVentaId').value) {
            event.preventDefault();
            alert('❌ Debes seleccionar una venta');
            return;
        }

        const productosSeleccionados = Object.values(this.productosSeleccionados);

        if (productosSeleccionados.length === 0) {
            event.preventDefault();
            alert('❌ Debes seleccionar al menos un producto a devolver');
            return;
        }

        if (!document.getElementById('crearDevMotivo').value.trim()) {
            event.preventDefault();
            alert('❌ Debes ingresar un motivo');
            return;
        }

        console.log('✅ Formulario válido, enviando...');
    },

    mostrar() {
        this.modal?.show();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Inicializando ModalCrearDevolucion...');
    window.ModalCrearDevolucion.init();
});

console.log('✅ modal-crear-devolucion.js cargado');