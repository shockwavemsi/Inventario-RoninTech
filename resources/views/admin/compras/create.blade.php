<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $config->nombre_empresa }} - Nueva Orden de Compra</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/compras-formularios.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/compras.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <div class="sidebar">
        <h3>{{ $config->nombre_empresa }}</h3>
        <div id="menu-contenedor"></div>
        <a href="{{ route('logout') }}" class="mt-4">
            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
        </a>
    </div>

    <div class="content">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="bi bi-cart-plus text-danger"></i> Nueva Orden de Compra
            </h1>
            <a href="{{ route('compras.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <form id="formCrearCompra" action="{{ route('compras.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="bi bi-info-circle"></i> Información General
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-barcode"></i> Código
                                </label>
                                <input type="text" class="form-control" id="preview_numero_factura" readonly>
                                <input type="hidden" name="numero_factura" id="numero_factura_hidden">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-shop"></i> Proveedor <span class="text-danger">*</span>
                                </label>
                                <select name="proveedor_id" id="proveedor_id" class="form-select" required>
                                    <option value="">-- Seleccione un proveedor --</option>
                                    @foreach($proveedores as $prov)
                                        <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i> Solo se mostrarán productos de este proveedor
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="bi bi-calendar"></i> Fechas
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-calendar-event"></i> Fecha Pedido <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="fecha_pedido" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-calendar-check"></i> Fecha Entrega Esperada
                                </label>
                                <input type="date" name="fecha_entrega_esperada" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-box-seam"></i> Productos
                </div>
                <div class="card-body">

                    <div class="row mb-4">
                        <div class="col-md-5">
                            <label class="form-label form-label-sm">Producto</label>
                            <select id="selector_producto" class="form-select" disabled>
                                <option value="">-- Primero seleccione un proveedor --</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label form-label-sm">Cantidad</label>
                            <input type="number" id="cantidad_producto" class="form-control" placeholder="1" value="1" min="0.01" step="0.01">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label form-label-sm">Precio</label>
                            <input type="number" id="precio_producto" class="form-control" placeholder="0.00" step="0.01">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label form-label-sm">&nbsp;</label>
                            <button type="button" class="btn btn-primary w-100" id="btn_agregar_producto" disabled>
                                <i class="bi bi-plus-circle"></i> Agregar
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="40%"><i class="bi bi-box"></i> Producto</th>
                                    <th width="15%" class="text-center"><i class="bi bi-hash"></i> Cantidad</th>
                                    <th width="15%" class="text-end"><i class="bi bi-cash-coin"></i> Precio</th>
                                    <th width="20%" class="text-end"><i class="bi bi-calculator"></i> Total</th>
                                    <th width="10%" class="text-center"><i class="bi bi-trash"></i></th>
                                </tr>
                            </thead>
                            <tbody id="productos_temp">
                                <tr>
                                    <td colspan="5" class="text-muted text-center py-4">
                                        <i class="bi bi-inbox"></i> Seleccione un proveedor para ver sus productos
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">
                                        <i class="bi bi-calculator"></i> SUB TOTAL:
                                    </td>
                                    <td class="text-end fw-bold" id="subtotal_temp">$0.00</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-chat-left-text"></i> Observaciones
                        </div>
                        <div class="card-body">
                            <textarea name="observaciones" class="form-control" rows="4" placeholder="Notas adicionales sobre la orden..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-graph-up"></i> Cálculos Finales
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0 small">
                                <tr>
                                    <td class="fw-bold"><i class="bi bi-calculator"></i> SUB TOTAL:</td>
                                    <td class="text-end">
                                        <input type="number" name="subtotal" id="subtotal_final" class="form-control form-control-sm" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold"><i class="bi bi-percent"></i> DESCUENTO (%):</td>
                                    <td class="text-end">
                                        <input type="number" id="descuento_porcentaje" class="form-control form-control-sm" value="0" step="0.01" min="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold"><i class="bi bi-percent"></i> IMPUESTO (%):</td>
                                    <td class="text-end">
                                        <input type="number" id="impuesto_porcentaje" class="form-control form-control-sm" value="18" step="0.01" min="0">
                                    </td>
                                </tr>
                                <tr class="border-top-2 pt-2">
                                    <td class="fw-bold fs-6"><i class="bi bi-money"></i> TOTAL:</td>
                                    <td class="text-end">
                                        <input type="number" name="total" id="total_final" class="form-control form-control-sm fw-bold" style="background: var(--color-neon-red); color: white; border: 1px solid var(--color-neon-red);" readonly>
                                    </td>
                                </tr>
                            </table>
                            <input type="hidden" name="impuesto" id="impuesto_valor">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i> Guardar Orden
                </button>
                <a href="{{ route('compras.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>

        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/menu.js') }}"></script>

    <script>
        let productosTemp = [];
        let productoIndex = 0;

        function cargarNumeroFactura() {
            fetch('/compras/ultimo-numero')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('preview_numero_factura').value = data.numero_factura;
                    document.getElementById('numero_factura_hidden').value = data.numero_factura;
                })
                .catch(err => console.error('Error:', err));
        }

        function cargarProductosPorProveedor(proveedorId) {
            if (!proveedorId) {
                document.getElementById('selector_producto').innerHTML = '<option value="">-- Primero seleccione un proveedor --</option>';
                document.getElementById('selector_producto').disabled = true;
                document.getElementById('btn_agregar_producto').disabled = true;
                return;
            }

            fetch(`/api/productos-por-proveedor/${proveedorId}`)
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById('selector_producto');
                    select.innerHTML = '<option value="">-- Seleccionar producto --</option>';
                    if (data.length === 0) {
                        select.innerHTML += '<option value="" disabled>No hay productos para este proveedor</option>';
                        select.disabled = true;
                        document.getElementById('btn_agregar_producto').disabled = true;
                    } else {
                        data.forEach(producto => {
                            select.innerHTML += `<option value="${producto.id}"
                                data-nombre="${producto.nombre}"
                                data-marca="${producto.marca || ''}"
                                data-modelo="${producto.modelo || ''}"
                                data-precio="${producto.precio_compra}">
                                ${producto.nombre} - ${producto.marca} - $${parseFloat(producto.precio_compra).toFixed(2)}
                            </option>`;
                        });
                        select.disabled = false;
                        document.getElementById('btn_agregar_producto').disabled = false;
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    document.getElementById('selector_producto').innerHTML = '<option value="">Error al cargar productos</option>';
                });
        }

        function recalcularTotales() {
            let subtotal = 0;
            productosTemp.forEach(p => { subtotal += p.subtotal; });
            const descuentoPorcentaje = parseFloat(document.getElementById('descuento_porcentaje').value) || 0;
            const descuentoMonto = subtotal * descuentoPorcentaje / 100;
            const impuestoPorcentaje = parseFloat(document.getElementById('impuesto_porcentaje').value) || 0;
            const baseImponible = subtotal - descuentoMonto;
            const impuestoMonto = baseImponible * impuestoPorcentaje / 100;
            const total = baseImponible + impuestoMonto;
            document.getElementById('subtotal_final').value = subtotal.toFixed(2);
            document.getElementById('subtotal_temp').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('total_final').value = total.toFixed(2);
            document.getElementById('impuesto_valor').value = impuestoMonto.toFixed(2);
        }

        function validarCamposProducto() {
            const selectProducto = document.getElementById('selector_producto').value;
            const cantidadInput = document.getElementById('cantidad_producto');
            const precioInput = document.getElementById('precio_producto');

            if (selectProducto) {
                cantidadInput.setAttribute('required', '');
                precioInput.setAttribute('required', '');
            } else {
                cantidadInput.removeAttribute('required');
                precioInput.removeAttribute('required');
                cantidadInput.value = 1;
                precioInput.value = '';
            }
        }

        function renderizarProductos() {
            const tbody = document.getElementById('productos_temp');
            tbody.innerHTML = '';
            if (productosTemp.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-muted text-center">No hay productos agregados</td></tr>';
                recalcularTotales();
                return;
            }

            productosTemp.forEach((p, idx) => {
                const row = `
                    <tr>
                        <td>
                            <strong>${p.nombre}</strong><br>
                            <small class="text-muted">${p.marca || '—'} ${p.modelo || '—'}</small>
                        </td>
                        <td class="text-center">
                            <input type="number" class="form-control form-control-sm cantidad-producto" data-idx="${idx}" value="${p.cantidad}" step="0.01" min="0.01" style="width: 100px;">
                        </td>
                        <td class="text-end">
                            <input type="number" class="form-control form-control-sm precio-producto" data-idx="${idx}" value="${p.precio_unitario.toFixed(2)}" step="0.01" min="0" style="width: 120px;">
                        </td>
                        <td class="text-end fw-bold">$${p.subtotal.toFixed(2)}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm eliminar-producto" data-idx="${idx}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });

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

        document.getElementById('proveedor_id').addEventListener('change', function() {
            cargarProductosPorProveedor(this.value);
        });

        document.getElementById('selector_producto').addEventListener('change', function() {
            validarCamposProducto();
            const option = this.options[this.selectedIndex];
            if (this.value) {
                document.getElementById('precio_producto').value = parseFloat(option.dataset.precio || 0).toFixed(2);
            }
        });

        document.getElementById('btn_agregar_producto').addEventListener('click', function() {
            const select = document.getElementById('selector_producto');
            const option = select.options[select.selectedIndex];
            const productoId = select.value;

            if (!productoId) {
                alert('Seleccione un producto');
                return;
            }

            const cantidad = parseFloat(document.getElementById('cantidad_producto').value) || 0;
            const precioUnitario = parseFloat(document.getElementById('precio_producto').value) || 0;

            if (cantidad <= 0) {
                alert('Cantidad debe ser mayor a 0');
                return;
            }

            if (precioUnitario <= 0) {
                alert('Precio debe ser mayor a 0');
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
            validarCamposProducto();
            renderizarProductos();
        });

        document.getElementById('descuento_porcentaje').addEventListener('input', recalcularTotales);
        document.getElementById('impuesto_porcentaje').addEventListener('input', recalcularTotales);

        document.getElementById('formCrearCompra').addEventListener('submit', function(e) {
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

        cargarNumeroFactura();

    </script>

</body>
</html>