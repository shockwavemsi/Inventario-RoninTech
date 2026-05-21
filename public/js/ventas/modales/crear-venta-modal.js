/**
 * Crear Venta Modal - Lógica del modal
 */
import { buscarProductos, crearVenta } from '../api.js';
import { UIManager } from '../ui.js';
import ModalManager from './modal-manager.js';

class CrearVentaModal {
    static filaIndex = 0;
    static lineas = [];

    static init() {
        this.inicializarEventos();
        this.inicializarBuscador();
        this.inicializarBuscadorClientes();
        console.log('✅ CrearVentaModal inicializado');
    }

    static inicializarEventos() {
        const modal = document.getElementById('modalVenta');
        if (!modal) return;

        modal.addEventListener('show.bs.modal', () => this.onModalOpen());

        const form = document.getElementById('formVenta');
        if (form) {
            form.noValidate = true;
            form.addEventListener('submit', (e) => this.onFormSubmit(e));
        }

        const documentoInput = document.getElementById('clienteDocumento');
        if (documentoInput) {
            documentoInput.addEventListener('input', () => {
                this.actualizarRequerimientoDocumento(this.obtenerTotalActual());
            });
        }

        const clienteInput = document.getElementById('cliente');
        if (clienteInput) {
            clienteInput.addEventListener('input', () => {
                const clienteId = document.getElementById('clienteId');
                if (clienteId) clienteId.value = '';
            });
        }
	}

    static inicializarBuscadorClientes() {
        const input = document.getElementById('cliente');
        const lista = document.getElementById('listaClientesVenta');

        if (!input || !lista) return;

        let timeoutId = null;

        input.addEventListener('focus', () => {
            this.buscarClientes('', lista);
        });

        input.addEventListener('input', () => {
            clearTimeout(timeoutId);
            const query = input.value.trim();

            if (query.length < 1) {
                timeoutId = setTimeout(() => this.buscarClientes('', lista), 150);
                return;
            }

            timeoutId = setTimeout(() => this.buscarClientes(query, lista), 250);
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('#cliente') && !e.target.closest('#listaClientesVenta')) {
                lista.style.display = 'none';
            }
        });
    }

    static async buscarClientes(query, lista) {
        try {
            const response = await fetch(`/clientes/buscar?q=${encodeURIComponent(query)}`);
            const clientes = await response.json();
            this.renderizarClientes(clientes, lista, query === '');
        } catch (error) {
            console.error('Error buscando clientes:', error);
            lista.style.display = 'none';
        }
    }

    static renderizarClientes(clientes, container, mostrandoRecientes = false) {
        container.innerHTML = '';

        if (!clientes.length) {
            container.innerHTML = '<div style="padding: 0.65rem; color: #a0a0a0; font-size: 0.85rem;">Sin coincidencias. Se creará al guardar si indicas documento.</div>';
            container.style.display = 'block';
            return;
        }

        if (mostrandoRecientes) {
            const header = document.createElement('div');
            header.style.cssText = 'padding: 0.45rem 0.7rem; color: #a0a0a0; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;';
            header.textContent = 'Clientes recientes';
            container.appendChild(header);
        }

        clientes.forEach(cliente => {
            const item = document.createElement('button');
            item.type = 'button';
            item.className = 'list-group-item list-group-item-action';
            item.style.cssText = 'padding: 0.7rem; color: #f0f0f0; background: rgba(230, 57, 70, 0.1); border-color: rgba(230, 57, 70, 0.2); text-align: left; font-size: 0.88rem;';
            item.innerHTML = `
                <strong>${cliente.nombre_completo}</strong>
                <br><small style="color: #a0a0a0;">${cliente.documento} · ${cliente.telefono || 'Sin teléfono'}</small>
            `;
            item.addEventListener('click', () => this.seleccionarCliente(cliente, container));
            container.appendChild(item);
        });

        container.style.display = 'block';
    }

    static seleccionarCliente(cliente, container) {
        document.getElementById('clienteId').value = cliente.id;
        document.getElementById('cliente').value = cliente.nombre_completo;
        document.getElementById('clienteDocumento').value = cliente.documento || '';
        document.getElementById('clienteTelefono').value = cliente.telefono || '';
        container.style.display = 'none';
        this.actualizarRequerimientoDocumento(this.obtenerTotalActual());
    }

    static async onModalOpen() {  // ✅ AGREGAR async
    console.log('✅ Modal venta abierto');
    await this.generarNumeroVenta();  // ✅ ESPERAR la respuesta
	    this.filaIndex = 0;
	    this.lineas = [];
        document.getElementById('clienteId').value = '';
        document.getElementById('clienteTelefono').value = '';
	    this.limpiarLineas();
	    this.calcularTotales();
	}

  static async generarNumeroVenta() {  // ✅ AGREGAR async
    try {
        console.log('📊 Obteniendo próximo número...');

        const response = await fetch('/ventas/proximo-numero');

        if (!response.ok) throw new Error('Error al obtener número');

        const data = await response.json();
        console.log('✅ Número obtenido:', data.numero);

        const input = document.getElementById('numeroVenta');
        if (input) input.value = data.numero;

	    } catch (error) {
	        console.error('❌ Error:', error);
	        // Fallback: usar el ID local si falla
	        const ultimoId = window.ultimoVentaId || 0;
	        const numeroVenta = 'FAC-V-' + String(ultimoId + 1).padStart(4, '0');
	        const input = document.getElementById('numeroVenta');
	        if (input) input.value = numeroVenta;
	    }
	}

    static inicializarBuscador() {
        const buscador = document.getElementById('buscadorProductos');
        const lista = document.getElementById('listaProductos');

        if (!buscador || !lista) return;

        buscador.addEventListener('input', async (e) => {
            const query = e.target.value.trim();

            if (query.length < 1) {
                lista.style.display = 'none';
                return;
            }

            const productos = await buscarProductos(query);
            this.renderizarProductos(productos, lista);
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('#buscadorProductos') && !e.target.closest('#listaProductos')) {
                lista.style.display = 'none';
            }
        });
    }

    static renderizarProductos(productos, container) {
        container.innerHTML = '';

        if (productos.length === 0) {
            container.innerHTML = '<p style="padding: 0.5rem; color: #a0a0a0;">No hay productos</p>';
            container.style.display = 'block';
            return;
        }

        productos.forEach(producto => {
            const item = document.createElement('div');
            item.className = 'list-group-item';
            item.style.cssText = 'padding: 0.75rem; color: #f0f0f0; cursor: pointer; background: rgba(230, 57, 70, 0.1); border-bottom: 1px solid rgba(230, 57, 70, 0.2); font-size: 0.9rem;';
            item.innerHTML = `
                <strong>${producto.nombre}</strong> (${producto.marca})
                <br><small style="color: #a0a0a0;">Modelo: ${producto.modelo} | Stock: <span style="color: #90ee90; font-weight: 600;">${producto.stock}</span></small>
                <br><small style="color: #e63946; font-weight: 600;">€${producto.precio.toFixed(2)}</small>
            `;

            item.addEventListener('click', () => {
                this.agregarLineaProducto(producto);
                document.getElementById('buscadorProductos').value = '';
                container.style.display = 'none';
            });

            container.appendChild(item);
        });

        container.style.display = 'block';
    }

    static agregarLineaProducto(producto) {
        // Validar duplicado
        if (this.lineas.find(l => l.producto_id === producto.id)) {
            UIManager.mostrarAlerta('⚠️ Este producto ya está en la lista', 'warning');
            return;
        }

        if (producto.stock <= 0) {
            UIManager.mostrarAlerta('❌ Sin stock disponible', 'error');
            return;
        }

        this.agregarFila(producto);
    }

    static agregarFila(producto = null) {
        const tbody = document.getElementById('lineasList');
        if (!tbody) return;

        const idx = this.filaIndex++;
        const productoId = producto?.id || '';
        const productoNombre = producto?.nombre || '';
        const stock = producto?.stock || 0;
        const precio = producto?.precio || 0;

        const tr = document.createElement('tr');
        tr.id = `linea-${idx}`;
        tr.style.borderBottom = '1px solid rgba(230, 57, 70, 0.1)';
        tr.innerHTML = `
            <td style="padding: 0.75rem; color: #a0a0a0; text-align: center; font-size: 0.875rem;">${idx + 1}</td>
            <td style="padding: 0.75rem; color: #f0f0f0; font-size: 0.875rem;">
                <strong>${productoNombre}</strong>
                <input type="hidden" name="producto_id[]" value="${productoId}" class="producto-id">
            </td>
            <td style="padding: 0.75rem; color: #90ee90; text-align: center; font-weight: 600; font-size: 0.875rem;">${stock}</td>
            <td style="padding: 0.75rem;">
                <input type="number" name="cantidad[]" value="1" min="1" max="${stock}" class="form-control form-control-sm cantidad-input" 
                    data-idx="${idx}" onchange="window.CrearVentaModal.calcularTotales()"
                    style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; text-align: center;">
            </td>
            <td style="padding: 0.75rem;">
                <input type="number" name="precio_unitario[]" value="${precio.toFixed(2)}" step="0.01" min="0" class="form-control form-control-sm precio-input"
    data-idx="${idx}" readonly
    style="background: rgba(100, 100, 100, 0.2); border-color: rgba(230, 57, 70, 0.2); color: #a0a0a0; text-align: right; cursor: not-allowed;">
            </td>
            <td style="padding: 0.75rem; color: #e63946; font-weight: 600; text-align: right; font-size: 0.875rem;">
                <span class="subtotal-linea" data-idx="${idx}">0.00€</span>
            </td>
            <td style="padding: 0.75rem; text-align: center;">
                <button type="button" class="btn btn-sm btn-danger" onclick="window.CrearVentaModal.eliminarFila(${idx})">✕</button>
            </td>
        `;

        tbody.appendChild(tr);

        this.lineas.push({
            indice: idx,
            producto_id: productoId,
            cantidad: 1,
            precio_unitario: precio,
        });

        this.calcularTotales();
    }

    static eliminarFila(idx) {
        const fila = document.getElementById(`linea-${idx}`);
        if (fila) fila.remove();

        this.lineas = this.lineas.filter(l => l.indice !== idx);
        this.calcularTotales();
    }

    static calcularTotales() {
    let subtotal = 0;

    const cantidadInputs = document.querySelectorAll('.cantidad-input');
    const precioInputs = document.querySelectorAll('.precio-input');
    const subtotalSpans = document.querySelectorAll('.subtotal-linea');

    cantidadInputs.forEach((input, idx) => {
        const cantidad = parseFloat(input.value) || 0;
        const precio = parseFloat(precioInputs[idx]?.value) || 0;
        const lineaSubtotal = cantidad * precio;

        subtotalSpans[idx].textContent = lineaSubtotal.toFixed(2) + '€';
        subtotal += lineaSubtotal;
    });

    // ✅ OBTENER IVA DEL INPUT
	    const selectIva = document.getElementById('ivaVentaSelect');
        const ivaPorcentaje = parseFloat(selectIva?.options[selectIva.selectedIndex]?.dataset.porcentaje) || 0;
	    const iva = subtotal * (ivaPorcentaje / 100);
    const total = subtotal + iva;

    document.getElementById('subtotalDisplay').textContent = subtotal.toFixed(2) + '€';
    document.getElementById('ivaDisplay').textContent = iva.toFixed(2) + '€';
    document.getElementById('totalDisplay').textContent = total.toFixed(2) + '€';
this.actualizarRequerimientoDocumento(total);
    this.actualizarJsonLineas();
}

static actualizarRequerimientoDocumento(total) {
    const documentoInput = document.getElementById('clienteDocumento');
    if (!documentoInput) return;

	    const label = documentoInput.parentElement?.querySelector('label');
    let aviso = document.getElementById('avisoDocumentoVenta');

    if (total > 3000) {
        documentoInput.required = false;
        documentoInput.placeholder = 'DNI/NIF/Pasaporte obligatorio';
        documentoInput.style.borderColor = documentoInput.value.trim() ? 'rgba(230, 57, 70, 0.3)' : '#ffc107';

        if (label) {
            label.textContent = 'Documento *';
        }

        if (!aviso) {
            aviso = document.createElement('small');
            aviso.id = 'avisoDocumentoVenta';
            aviso.style.display = 'block';
            aviso.style.color = '#ffc107';
            aviso.style.marginTop = '0.25rem';
            aviso.textContent = 'Para ventas superiores a 3.000€ debes identificar al cliente con DNI/NIF.';
            documentoInput.parentNode.appendChild(aviso);
        }
    } else {
        documentoInput.required = false;
        documentoInput.placeholder = 'DNI/NIF/Pasaporte';
        documentoInput.style.borderColor = 'rgba(230, 57, 70, 0.3)';

        if (label) {
            label.textContent = 'Documento (Opcional)';
        }

        if (aviso) {
            aviso.remove();
        }
    }
}

static obtenerTotalActual() {
    let subtotal = 0;

    const cantidadInputs = document.querySelectorAll('.cantidad-input');
    const precioInputs = document.querySelectorAll('.precio-input');

    cantidadInputs.forEach((input, idx) => {
        const cantidad = parseFloat(input.value) || 0;
        const precio = parseFloat(precioInputs[idx]?.value) || 0;
        subtotal += cantidad * precio;
    });

	    const selectIva = document.getElementById('ivaVentaSelect');
        const ivaPorcentaje = parseFloat(selectIva?.options[selectIva.selectedIndex]?.dataset.porcentaje) || 0;
	    return subtotal + (subtotal * (ivaPorcentaje / 100));
	}

    static actualizarJsonLineas() {
        const cantidadInputs = document.querySelectorAll('.cantidad-input');
        const precioInputs = document.querySelectorAll('.precio-input');
        const productoIds = document.querySelectorAll('.producto-id');

        const lineas = [];
        cantidadInputs.forEach((input, idx) => {
            lineas.push({
                producto_id: parseInt(productoIds[idx]?.value) || 0,
                cantidad: parseInt(input.value) || 0,
                precio_unitario: parseFloat(precioInputs[idx]?.value) || 0,
            });
        });

        document.getElementById('lineasJson').value = JSON.stringify(lineas);
    }

    static limpiarLineas() {
        const tbody = document.getElementById('lineasList');
        if (tbody) tbody.innerHTML = '';
    }

    static onFormSubmit(e) {
    console.log('📤 [FORM] Submit iniciado');

    if (this.lineas.length === 0) {
        e.preventDefault();
        console.error('❌ [FORM] No hay líneas');
        alert('❌ Debes agregar líneas');
        return;
    }

    // Obtener datos del formulario
    const formData = new FormData(document.getElementById('formVenta'));

    console.log('📋 [FORM] Datos del formulario:');
    console.log('  - Número Venta:', formData.get('numero_factura'));
    console.log('  - Cliente:', formData.get('cliente'));
    console.log('  - Método Pago ID:', formData.get('metodo_pago_id'));
    console.log('  - Estado:', formData.get('estado'));
    console.log('  - Líneas JSON:', formData.get('lineas'));
    console.log('  - IVA ID:', formData.get('iva_id'));

    // Validar datos
    if (!formData.get('numero_factura')) {
        console.error('❌ Falta número de venta');
        e.preventDefault();
        return;
    }
    if (!formData.get('cliente')) {
        console.error('❌ Falta cliente');
        e.preventDefault();
        return;
    }
    if (!formData.get('metodo_pago_id')) {
        console.error('❌ Falta método de pago');
        e.preventDefault();
        return;
    }
    if (!formData.get('iva_id')) {
        console.error('❌ Falta IVA');
        e.preventDefault();
        return;
    }
const total = this.obtenerTotalActual();
const documentoInput = document.getElementById('clienteDocumento');
const documento = formData.get('cliente_documento')?.trim() || '';

if (total > 3000 && !documento) {
    e.preventDefault();
    this.actualizarRequerimientoDocumento(total);
    alert('Para ventas superiores a 3.000 EUR es obligatorio indicar el DNI/NIF del cliente.');
    if (documentoInput) {
        documentoInput.required = false;
        documentoInput.style.borderColor = '#dc3545';
        documentoInput.focus();
    }

    return;
}
    console.log('✅ [FORM] Datos válidos, enviando...');
}

    static mostrar() {
        ModalManager.mostrarVenta();
    }

    static ver(id) {
        console.log('👁️ Ver venta:', id);
        // TODO: Implementar visualización
    }
}

export default CrearVentaModal;
