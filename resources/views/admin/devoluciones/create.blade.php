<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $config->nombre_empresa }} - Nueva Devolución</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/menu.js') }}"></script>
</head>
<body>
    <div class="sidebar">
        <h3>{{ $config->nombre_empresa }}</h3>
        <div id="menu-contenedor"></div>
        <a href="{{ route('logout') }}" class="mt-4">Cerrar sesión</a>
    </div>

    <div class="content">
        <h1 class="mb-4">Nueva Devolución de Venta</h1>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>❌ Errores:</strong>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form id="formDevolucion" action="{{ route('devoluciones.store') }}" method="POST">
            @csrf

            <!-- SELECCIONAR VENTA -->
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Seleccionar Venta</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="form-label">Venta <span class="text-danger">*</span></label>
                            <select name="venta_id" id="venta_select" class="form-select bg-dark text-light border-secondary" required>
                                <option value="">Selecciona una venta...</option>
                                @foreach($ventas as $venta)
                                    <option value="{{ $venta->id }}" data-cliente="{{ $venta->cliente }}" data-total="{{ $venta->total }}">
                                        {{ $venta->numero_factura }} - {{ $venta->cliente }} - ${{ number_format($venta->total, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cliente</label>
                            <input type="text" id="cliente_venta" class="form-control bg-dark text-light border-secondary" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PRODUCTOS DE LA VENTA -->
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Productos Vendidos</h5>
                </div>
                <div class="card-body">
                    <table class="table table-dark table-bordered">
                        <thead>
                            <tr>
                                <th>Devolver</th>
                                <th>Producto</th>
                                <th class="text-center">Cant. Original</th>
                                <th class="text-center">Cant. Devolver</th>
                                <th class="text-center">Precio Unit</th>
                                <th class="text-center">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-productos-venta">
                            <tr>
                                <td colspan="6" class="text-muted text-center">Selecciona una venta para ver sus productos</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-success">
                                <td colspan="5" class="text-end"><strong>TOTAL DEVOLUCIÓN:</strong></td>
                                <td class="text-center"><strong id="total_devolucion">$0.00</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- INFORMACIÓN ADICIONAL -->
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Detalles de Devolución</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Motivo <span class="text-danger">*</span></label>
                            <textarea name="motivo" id="motivo" class="form-control bg-dark text-light border-secondary" rows="3" required placeholder="Describe el motivo...">{{ old('motivo') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado <span class="text-danger">*</span></label>
                            <select name="estado" class="form-select bg-dark text-light border-secondary" required>
                                <option value="pendiente" selected>Pendiente</option>
                                <option value="completada">Completada</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CAMPOS OCULTOS -->
            <input type="hidden" name="productos_json" id="productos_json" value="[]">
            <input type="hidden" name="total_devuelto" id="total_devuelto_input">

            <!-- BOTONES -->
            <div class="text-center">
                <button type="submit" class="btn btn-success btn-lg">Guardar Devolución</button>
                <a href="{{ route('devoluciones.index') }}" class="btn btn-secondary btn-lg">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let productosSeleccionados = [];

        // Cuando selecciona una venta
        document.getElementById('venta_select').addEventListener('change', function() {
            const ventaId = this.value;
            const option = this.options[this.selectedIndex];
            const cliente = option.dataset.cliente || '';

            document.getElementById('cliente_venta').value = cliente;
            productosSeleccionados = [];

            if (!ventaId) {
                document.getElementById('tabla-productos-venta').innerHTML = 
                    '<tr><td colspan="6" class="text-muted text-center">Selecciona una venta para ver sus productos</td></tr>';
                return;
            }

            // Obtener productos de la venta
            fetch(`/ventas/${ventaId}/json`)
                .then(res => res.json())
                .then(venta => {
                    renderizarProductosVenta(venta.detalles);
                })
                .catch(err => {
                    console.error(err);
                    alert('Error al cargar productos');
                });
        });

        function renderizarProductosVenta(detalles) {
            const tbody = document.getElementById('tabla-productos-venta');
            tbody.innerHTML = '';

            if (detalles.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-muted text-center">No hay productos en esta venta</td></tr>';
                calcularTotal();
                return;
            }

            detalles.forEach((detalle, idx) => {
                const fila = `
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" class="checkbox-devolver" data-idx="${idx}" data-producto-id="${detalle.producto_id}" data-precio="${detalle.precio_unitario}">
                        </td>
                        <td>${detalle.producto.nombre}</td>
                        <td class="text-center">${detalle.cantidad}</td>
                        <td class="text-center">
                            <input type="number" class="form-control form-control-sm cantidad-devolver" data-idx="${idx}" min="0" max="${detalle.cantidad}" value="0" style="width: 80px;">
                        </td>
                        <td class="text-center">$${parseFloat(detalle.precio_unitario).toFixed(2)}</td>
                        <td class="text-center subtotal-${idx}">$0.00</td>
                    </tr>
                `;
                tbody.innerHTML += fila;
            });

            // Event listeners para checkboxes
            document.querySelectorAll('.checkbox-devolver').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const idx = this.dataset.idx;
                    const cantidadInput = document.querySelector(`.cantidad-devolver[data-idx="${idx}"]`);

                    if (this.checked) {
                        cantidadInput.value = detalles[idx].cantidad;
                    } else {
                        cantidadInput.value = 0;
                    }

                    calcularTotal();
                });
            });

            // Event listeners para cantidad
            document.querySelectorAll('.cantidad-devolver').forEach(input => {
                input.addEventListener('change', calcularTotal);
            });

            calcularTotal();
        }

        function calcularTotal() {
            let totalDevolucion = 0;
            productosSeleccionados = [];

            document.querySelectorAll('.checkbox-devolver:checked').forEach(checkbox => {
                const idx = checkbox.dataset.idx;
                const cantidadInput = document.querySelector(`.cantidad-devolver[data-idx="${idx}"]`);
                const cantidad = parseInt(cantidadInput.value) || 0;
                const precio = parseFloat(checkbox.dataset.precio);

                if (cantidad > 0) {
                    const subtotal = cantidad * precio;
                    totalDevolucion += subtotal;

                    document.querySelector(`.subtotal-${idx}`).textContent = '$' + subtotal.toFixed(2);

                    productosSeleccionados.push({
                        producto_id: checkbox.dataset.productoId,
                        cantidad: cantidad,
                        precio_unitario: precio,
                        subtotal: subtotal
                    });
                } else {
                    document.querySelector(`.subtotal-${idx}`).textContent = '$0.00';
                }
            });

            document.getElementById('total_devolucion').textContent = '$' + totalDevolucion.toFixed(2);
            document.getElementById('total_devuelto_input').value = totalDevolucion.toFixed(2);
            document.getElementById('productos_json').value = JSON.stringify(productosSeleccionados);
        }

        // Validar antes de enviar
        document.getElementById('formDevolucion').addEventListener('submit', function(e) {
            if (productosSeleccionados.length === 0) {
                e.preventDefault();
                alert('❌ Debes seleccionar al menos un producto para devolver');
            }
        });
    </script>

</body>
</html>