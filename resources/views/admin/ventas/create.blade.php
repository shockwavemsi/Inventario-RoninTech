<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $config->nombre_empresa }} - Crear Venta</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/compras.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
    $user = auth()->user();
    $roleName = $user->role->name ?? 'user';  // Accede al nombre del rol
    $menuScript = $roleName === 'admin' ? 'js/menu.js' : 'js/userMenu.js';
@endphp
<script src="{{ asset($menuScript) }}"></script>

</head>

<body>

    <!-- BOTÓN HAMBURGUESA -->
    <button id="menu-toggle" class="menu-toggle" aria-label="Abrir menú">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <!-- OVERLAY -->
    <div id="sidebar-overlay" class="sidebar-overlay"></div>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h3>{{ $config->nombre_empresa }}</h3>
        <div id="menu-contenedor"></div>
        <a href="{{ route('logout') }}" class="mt-4">
            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
        </a>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="content">

        <h1 class="mb-4">
            <i class="bi bi-plus-circle"></i> Crear Venta
        </h1>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="bi bi-exclamation-circle-fill"></i> Errores:</strong>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="bi bi-exclamation-circle-fill"></i> Error:</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form id="formVenta" action="{{ route('ventas.store') }}" method="POST">
            @csrf

            <!-- DATOS PRINCIPALES -->
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información de Venta</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label"><strong>Número de Venta</strong></label>
                            <input type="text" id="numero_factura_input" name="numero_factura" class="form-control bg-dark text-light border-secondary" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><strong>Cliente <span class="text-danger">*</span></strong></label>
                            <input type="text" name="cliente" id="cliente" class="form-control bg-dark text-light border-secondary" value="{{ old('cliente') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><strong>Documento Cliente</strong></label>
                            <input type="text" name="cliente_documento" class="form-control bg-dark text-light border-secondary" value="{{ old('cliente_documento') }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Método de Pago <span class="text-danger">*</span></strong></label>
                            <select name="metodo_pago" class="form-select bg-dark text-light border-secondary" required>
                                <option value="">Selecciona...</option>
                                <option value="efectivo" {{ old('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                                <option value="tarjeta" {{ old('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                                <option value="transferencia" {{ old('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Estado <span class="text-danger">*</span></strong></label>
                            <select name="estado" class="form-select bg-dark text-light border-secondary" required>
                                <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="completada" {{ old('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="form-label"><strong>Observaciones</strong></label>
                            <textarea name="observaciones" class="form-control bg-dark text-light border-secondary" rows="2">{{ old('observaciones') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AGREGAR PRODUCTOS -->
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-box-seam"></i> Productos</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <label class="form-label"><strong>Producto</strong></label>
                            <select id="producto_select" class="form-select bg-dark text-light border-secondary">
                                <option value="">Selecciona un producto...</option>
                                @foreach($productos as $prod)
                                    <option value="{{ $prod->id }}" data-precio="{{ $prod->precio_venta }}" data-stock="{{ $prod->stock_actual }}">
                                        {{ $prod->nombre }} (Stock: {{ $prod->stock_actual }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label"><strong>Cantidad</strong></label>
                            <input type="number" id="cantidad" class="form-control bg-dark text-light border-secondary" min="1" value="1">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-primary w-100" id="agregar_producto">
                                <i class="bi bi-plus-circle"></i> Agregar
                            </button>
                        </div>
                    </div>

                    <!-- TABLA DE PRODUCTOS -->
                    <div class="table-responsive">
                        <table class="table table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center" style="width: 80px;">Cant</th>
                                    <th class="text-center" style="width: 100px;">Precio Unit</th>
                                    <th class="text-center" style="width: 100px;">Subtotal</th>
                                    <th class="text-center" style="width: 50px;">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-productos">
                                <!-- Se agrega dinámicamente -->
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-center"><strong id="subtotal_venta">$0.00</strong></td>
                                    <td></td>
                                </tr>
                                <tr class="table-light">
                                    <td colspan="3" class="text-end"><strong>Impuesto (21%):</strong></td>
                                    <td class="text-center"><strong id="impuesto_venta">$0.00</strong></td>
                                    <td></td>
                                </tr>
                                <tr class="table-success">
                                    <td colspan="3" class="text-end"><strong>TOTAL:</strong></td>
                                    <td class="text-center"><strong id="total_venta">$0.00</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <input type="hidden" name="productos_json" id="productos_json">
                    <input type="hidden" name="subtotal" id="subtotal">
                    <input type="hidden" name="impuesto" id="impuesto" value="0">
                    <input type="hidden" name="total" id="total_input">
                </div>
            </div>

            <!-- BOTONES -->
            <div class="text-center">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="bi bi-check-circle"></i> Guardar Venta
                </button>
                <a href="{{ route('ventas.index') }}" class="btn btn-secondary btn-lg">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>

        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>

        // BOTÓN HAMBURGUESA
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('activo');
            overlay.classList.toggle('activo');
        });

        overlay.addEventListener('click', function() {
            sidebar.classList.remove('activo');
            overlay.classList.remove('activo');
        });

        // Cerrar sidebar al hacer click en un link
        document.querySelectorAll('.sidebar a').forEach(link => {
            link.addEventListener('click', function() {
                sidebar.classList.remove('activo');
                overlay.classList.remove('activo');
            });
        });

        let productos_en_venta = [];

        function obtenerProximoNumero() {
            fetch('/ventas/proximo-numero')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('numero_factura_input').value = data.numero;
                })
                .catch(err => console.error('Error:', err));
        }

        document.addEventListener('DOMContentLoaded', function() {
            obtenerProximoNumero();

            document.getElementById('producto_select').addEventListener('change', function() {
                const precio = this.options[this.selectedIndex].dataset.precio || 0;
                document.getElementById('cantidad').value = 1;
            });

            document.getElementById('agregar_producto').addEventListener('click', agregarProducto);

            document.getElementById('formVenta').addEventListener('submit', function(e) {
                e.preventDefault();
                if (productos_en_venta.length === 0) {
                    alert('❌ Debes agregar al menos un producto');
                    return;
                }
                document.getElementById('productos_json').value = JSON.stringify(productos_en_venta);
                this.submit();
            });
        });

        function agregarProducto() {
            const selectElement = document.getElementById('producto_select');
            const producto_id = selectElement.value;
            const cantidad = parseInt(document.getElementById('cantidad').value) || 1;
            const option = selectElement.options[selectElement.selectedIndex];
            const stock_disponible = parseInt(option.dataset.stock) || 0;
            const precio_unitario = parseFloat(option.dataset.precio) || 0;
            const producto_nombre = option.text;

            if (!producto_id) {
                alert('❌ Selecciona un producto');
                return;
            }

            if (cantidad > stock_disponible) {
                alert(`⚠️ No hay suficiente stock.\nDisponible: ${stock_disponible}\nSolicitado: ${cantidad}`);
                return;
            }

            if (stock_disponible <= 0) {
                alert(`❌ El producto está agotado`);
                return;
            }

            if (cantidad <= 0) {
                alert('❌ Cantidad debe ser mayor a 0');
                return;
            }

            const subtotal = cantidad * precio_unitario;

            productos_en_venta.push({
                producto_id: producto_id,
                cantidad: cantidad,
                precio_unitario: precio_unitario,
                subtotal: subtotal
            });

            selectElement.value = '';
            document.getElementById('cantidad').value = '1';

            renderizarProductos();
        }

        function eliminarProducto(e) {
            const fila = e.target.closest('tr');
            const index = Array.from(document.querySelectorAll('#tabla-productos tr')).indexOf(fila);
            productos_en_venta.splice(index, 1);
            renderizarProductos();
        }

        function renderizarProductos() {
            const tbody = document.getElementById('tabla-productos');
            tbody.innerHTML = '';

            if (productos_en_venta.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-muted text-center">No hay productos agregados</td></tr>';
                calcularTotal();
                return;
            }

            productos_en_venta.forEach((p, idx) => {
                const option = document.querySelector(`#producto_select option[value="${p.producto_id}"]`);
                const nombre = option ? option.text.split(' - ')[0] : 'Producto';

                const fila = `
                    <tr>
                        <td>${nombre}</td>
                        <td class="text-center">${p.cantidad}</td>
                        <td class="text-center">$${p.precio_unitario.toFixed(2)}</td>
                        <td class="text-center">$${p.subtotal.toFixed(2)}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm eliminar-producto">✕</button>
                        </td>
                    </tr>
                `;

                tbody.innerHTML += fila;
            });

            document.querySelectorAll('.eliminar-producto').forEach(btn => {
                btn.addEventListener('click', eliminarProducto);
            });

            calcularTotal();
        }

        function calcularTotal() {
            const subtotal = productos_en_venta.reduce((sum, p) => sum + p.subtotal, 0);
            const impuesto = subtotal * 0.21;
            const total = subtotal + impuesto;

            document.getElementById('subtotal_venta').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('impuesto_venta').textContent = '$' + impuesto.toFixed(2);
            document.getElementById('total_venta').textContent = '$' + total.toFixed(2);

            document.getElementById('subtotal').value = subtotal.toFixed(2);
            document.getElementById('impuesto').value = impuesto.toFixed(2);
            document.getElementById('total_input').value = total.toFixed(2);
        }

    </script>

</body>
</html>