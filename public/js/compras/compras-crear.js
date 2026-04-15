// public/js/compras/compras-crear.js

// Variables globales
let productosTemp = [];
let productoIndex = 0;

// Función para cargar el número de factura
function cargarNumeroFactura() {
    fetch('/compras/ultimo-numero')
        .then(res => res.json())
        .then(data => {
            const preview = document.getElementById('preview_numero_factura');
            const hidden = document.getElementById('numero_factura_hidden');
            if (preview) preview.value = data.numero_factura;
            if (hidden) hidden.value = data.numero_factura;
        })
        .catch(err => console.error('Error:', err));
}

// Función para cargar productos por proveedor
function cargarProductosPorProveedor(proveedorId) {
    if (!proveedorId) {
        const select = document.getElementById('selector_producto');
        if (select) {
            select.innerHTML = '<option value="">-- Primero seleccione un proveedor --</option>';
            select.disabled = true;
        }
        const btn = document.getElementById('btn_agregar_producto');
        if (btn) btn.disabled = true;
        return;
    }
    
    fetch(`/api/productos-por-proveedor/${proveedorId}`)
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('selector_producto');
            if (!select) return;
            
            select.innerHTML = '<option value="">-- Seleccionar producto --</option>';
            
            if (data.length === 0) {
                select.innerHTML += '<option value="" disabled>No hay productos para este proveedor</option>';
                select.disabled = true;
                const btn = document.getElementById('btn_agregar_producto');
                if (btn) btn.disabled = true;
            } else {
                data.forEach(producto => {
                    select.innerHTML += `<option value="${producto.id}" 
                        data-nombre="${producto.nombre}"
                        data-marca="${producto.marca}"
                        data-modelo="${producto.modelo}"
                        data-precio="${producto.precio_compra}">
                        ${producto.nombre} - ${producto.marca} - $${parseFloat(producto.precio_compra).toFixed(2)}
                    </option>`;
                });
                select.disabled = false;
                const btn = document.getElementById('btn_agregar_producto');
                if (btn) btn.disabled = false;
            }
        })
        .catch(err => {
            console.error('Error:', err);
            const select = document.getElementById('selector_producto');
            if (select) select.innerHTML = '<option value="">Error al cargar productos</option>';
        });
}

// Función para recalcular totales
function recalcularTotales() {
    let subtotal = 0;
    productosTemp.forEach(p => { subtotal += p.subtotal; });
    
    const descuentoPorcentaje = parseFloat(document.getElementById('descuento_porcentaje')?.value) || 0;
    const descuentoMonto = subtotal * descuentoPorcentaje / 100;
    const impuestoPorcentaje = parseFloat(document.getElementById('impuesto_porcentaje')?.value) || 0;
    const baseImponible = subtotal - descuentoMonto;
    const impuestoMonto = baseImponible * impuestoPorcentaje / 100;
    const total = baseImponible + impuestoMonto;
    
    const subtotalFinal = document.getElementById('subtotal_final');
    const subtotalTemp = document.getElementById('subtotal_temp');
    const totalFinal = document.getElementById('total_final');
    
    if (subtotalFinal) subtotalFinal.value = subtotal.toFixed(2);
    if (subtotalTemp) subtotalTemp.textContent = '$' + subtotal.toFixed(2);
    if (totalFinal) totalFinal.value = total.toFixed(2);
}

// Renderizar productos en la tabla
function renderizarProductos() {
    const tbody = document.getElementById('productos_temp');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    if (productosTemp.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-muted text-center">No hay productos agregados</td></tr>';
        recalcularTotales();
        return;
    }
    
    productosTemp.forEach((p, idx) => {
        const row = `
            <tr>
                <td><strong>${p.nombre}</strong><br><small class="text-muted">${p.marca} ${p.modelo}</small></td>
                <td><input type="number" class="form-control form-control-sm cantidad-producto" data-idx="${idx}" value="${p.cantidad}" step="0.01" min="0.01" style="width: 100px;"></td>
                <td><input type="number" class="form-control form-control-sm precio-producto" data-idx="${idx}" value="${p.precio_unitario.toFixed(2)}" step="0.01" min="0" style="width: 120px;"></td>
                <td class="fw-bold">$${p.subtotal.toFixed(2)}</td>
                <td><button type="button" class="btn btn-danger btn-sm eliminar-producto" data-idx="${idx}"><i class="bi bi-trash"></i></button></td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
    
    // Eventos
    document.querySelectorAll('.cantidad-producto').forEach(input => {
        input.addEventListener('change', function(e) {
            const idx = parseInt(e.target.dataset.idx);
            productosTemp[idx].cantidad = parseFloat(e.target.value) || 0;
            productosTemp[idx].subtotal = productosTemp[idx].cantidad * productosTemp[idx].precio_unitario;
            renderizarProductos();
        });
    });
    
    document.querySelectorAll('.precio-producto').forEach(input => {
        input.addEventListener('change', function(e) {
            const idx = parseInt(e.target.dataset.idx);
            productosTemp[idx].precio_unitario = parseFloat(e.target.value) || 0;
            productosTemp[idx].subtotal = productosTemp[idx].cantidad * productosTemp[idx].precio_unitario;
            renderizarProductos();
        });
    });
    
    document.querySelectorAll('.eliminar-producto').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const idx = parseInt(e.target.closest('.eliminar-producto').dataset.idx);
            productosTemp.splice(idx, 1);
            renderizarProductos();
        });
    });
    
    recalcularTotales();
}

// Inicializar eventos
document.addEventListener('DOMContentLoaded', function() {
    // Cargar número de factura
    cargarNumeroFactura();
    
    // Evento cambio de proveedor
    const proveedorSelect = document.getElementById('proveedor_id');
    if (proveedorSelect) {
        proveedorSelect.addEventListener('change', function() {
            cargarProductosPorProveedor(this.value);
        });
    }
    
    // Evento para agregar producto
    const btnAgregar = document.getElementById('btn_agregar_producto');
    if (btnAgregar) {
        btnAgregar.addEventListener('click', function() {
            const select = document.getElementById('selector_producto');
            const option = select.options[select.selectedIndex];
            const productoId = select.value;
            
            if (!productoId) {
                alert('Seleccione un producto');
                return;
            }
            
            const cantidad = parseFloat(document.getElementById('cantidad_producto').value) || 1;
            const precioUnitario = parseFloat(document.getElementById('precio_producto').value) || 0;
            
            if (cantidad <= 0 || precioUnitario <= 0) {
                alert('Cantidad y precio deben ser mayores a 0');
                return;
            }
            
            productosTemp.push({
                id: productoIndex++,
                producto_id: productoId,
                nombre: option.dataset.nombre,
                marca: option.dataset.marca || '—',
                modelo: option.dataset.modelo || '—',
                cantidad: cantidad,
                precio_unitario: precioUnitario,
                subtotal: cantidad * precioUnitario
            });
            
            select.value = '';
            document.getElementById('cantidad_producto').value = 1;
            document.getElementById('precio_producto').value = '';
            renderizarProductos();
        });
    }
    
    // Selector de producto - cargar precio
    const selectorProducto = document.getElementById('selector_producto');
    if (selectorProducto) {
        selectorProducto.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            if (this.value) {
                document.getElementById('precio_producto').value = parseFloat(option.dataset.precio || 0).toFixed(2);
            }
        });
    }
    
    // Descuento e impuesto
    const descuentoInput = document.getElementById('descuento_porcentaje');
    const impuestoInput = document.getElementById('impuesto_porcentaje');
    
    if (descuentoInput) descuentoInput.addEventListener('input', recalcularTotales);
    if (impuestoInput) impuestoInput.addEventListener('input', recalcularTotales);
    
    // Submit del formulario
    const form = document.getElementById('formCrearCompra');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (productosTemp.length === 0) {
                e.preventDefault();
                alert('Debe agregar al menos un producto');
                return;
            }
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'productos_json';
            input.value = JSON.stringify(productosTemp);
            this.appendChild(input);
        });
    }
});