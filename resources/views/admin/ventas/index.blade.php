<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $config->nombre_empresa }} - Ventas</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/compras.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/menu.js') }}"></script>

    <!-- ESTILOS PERSONALIZADOS PARA TABLA -->
    <style>
        /* Override Bootstrap para tema cyberpunk */
        .table {
            color: #f0f0f0;
        }

        .table-dark {
            background-color: rgba(15, 15, 20, 0.9) !important;
        }

        .table-dark thead th {
            border-color: #e63946 !important;
            background-color: rgba(230, 57, 70, 0.15) !important;
        }

        .table-dark tbody + thead th {
            border-color: #e63946 !important;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(230, 57, 70, 0.1) !important;
            color: #f0f0f0;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(20, 20, 25, 0.3) !important;
        }

        .table-striped tbody tr:nth-of-type(even) {
            background-color: transparent;
        }

        .table-bordered {
            border-color: rgba(230, 57, 70, 0.2) !important;
        }

        .table-bordered th,
        .table-bordered td {
            border-color: rgba(230, 57, 70, 0.2) !important;
        }
    </style>

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

        <h1>
            <i class="bi bi-bag-check"></i> Ventas
        </h1>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        <!-- TARJETAS ESTADÍSTICAS -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-bag-check" style="font-size: 2.5rem; color: var(--neon-red); opacity: 0.7;"></i>
                        <div>
                            <div class="stat-label">Total de Ventas</div>
                            <div class="stat-value">{{ count($ventas) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-hourglass-split" style="font-size: 2.5rem; color: var(--neon-red); opacity: 0.7;"></i>
                        <div>
                            <div class="stat-label">Pendientes</div>
                            <div class="stat-value">{{ $ventas->where('estado', 'pendiente')->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-check-circle-fill" style="font-size: 2.5rem; color: var(--neon-red); opacity: 0.7;"></i>
                        <div>
                            <div class="stat-label">Completadas</div>
                            <div class="stat-value">{{ $ventas->where('estado', 'completada')->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-cash-coin" style="font-size: 2.5rem; color: var(--neon-red); opacity: 0.7;"></i>
                        <div>
                            <div class="stat-label">Valor Total</div>
                            <div class="stat-value">${{ number_format($ventas->sum('total'), 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTROS Y CONTROLES -->
        <div class="d-flex justify-content-between align-items-center mb-4 gap-3">
            <a href="{{ route('ventas.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nueva Venta
            </a>

            <div class="d-flex gap-2" style="flex: 1; max-width: 600px;">
                <select id="filtro-estado" class="form-select filtro-select">
                    <option value="todos">Todos</option>
                    <option value="completada">Completadas</option>
                    <option value="pendiente">Pendientes</option>
                </select>
                <input type="text" id="buscador" class="form-control" placeholder="🔍 Buscar por código o cliente...">
            </div>
        </div>

        <!-- TABLA DE VENTAS -->
        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover table-bordered">
                <thead>
                    <tr class="table-active">
                        <th><i class="bi bi-barcode"></i> Código</th>
                        <th><i class="bi bi-calendar"></i> Fecha</th>
                        <th><i class="bi bi-person"></i> Cliente</th>
                        <th><i class="bi bi-cash-coin"></i> Total</th>
                        <th><i class="bi bi-info-circle"></i> Estado</th>
                        <th><i class="bi bi-person-badge"></i> Usuario</th>
                        <th style="width: 150px"><i class="bi bi-gear"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-ventas">
                    @foreach($ventas as $v)
                        <tr data-estado="{{ $v->estado }}" data-codigo="{{ $v->numero_factura }}" data-cliente="{{ $v->cliente }}">
                            <td class="codigo">
                                <i class="bi bi-receipt"></i> {{ $v->numero_factura }}
                            </td>
                            <td>{{ $v->fecha_venta->format('d/m/Y') }}</td>
                            <td class="cliente">{{ $v->cliente }}</td>
                            <td><strong>${{ number_format($v->total, 2) }}</strong></td>
                            <td>
                                <span class="badge estado-badge px-3 py-2
                                    @if($v->estado === 'pendiente') bg-warning text-dark
                                    @elseif($v->estado === 'completada') bg-success
                                    @else bg-secondary @endif">
                                    <i class="bi 
                                        @if($v->estado === 'pendiente') bi-clock-history
                                        @elseif($v->estado === 'completada') bi-check-circle-fill
                                        @else bi-question-circle @endif
                                        me-1"></i>
                                    {{ ucfirst($v->estado) }}
                                </span>
                            </td>
                            <td>{{ $v->usuario->name ?? '—' }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-info ver-venta" 
                                            data-id="{{ $v->id }}" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @if($v->estado === 'pendiente')
                                        <button type="button" class="btn btn-success confirmar-venta" 
                                                data-id="{{ $v->id }}" title="Confirmar venta">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-danger eliminar-venta" 
                                            data-id="{{ $v->id }}" title="Eliminar venta">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p id="contador">
            <i class="bi bi-info-circle"></i> <strong>Mostrando {{ count($ventas) }} ventas</strong>
        </p>

    </div>

    <!-- MODAL VER VENTA -->
    <div class="modal fade" id="modalVerVenta" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-receipt"></i> Detalles de la Venta
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-3">
                                        <i class="bi bi-info-circle"></i> Información General
                                    </h6>
                                    <p class="mb-2">
                                        <strong>Código:</strong><br>
                                        <span id="ver_codigo" class="badge bg-dark py-2"></span>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Cliente:</strong><br>
                                        <span id="ver_cliente"></span>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Documento:</strong><br>
                                        <span id="ver_documento"></span>
                                    </p>
                                    <p class="mb-0">
                                        <strong>Fecha Venta:</strong><br>
                                        <span id="ver_fecha"></span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-3">
                                        <i class="bi bi-dash-circle"></i> Estado y Detalles
                                    </h6>
                                    <p class="mb-2">
                                        <strong>Estado:</strong><br>
                                        <span id="ver_estado" class="badge"></span>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Método de Pago:</strong><br>
                                        <span id="ver_metodo"></span>
                                    </p>
                                    <p class="mb-0">
                                        <strong>Usuario:</strong><br>
                                        <span id="ver_usuario"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6 class="mb-3">
                        <i class="bi bi-bag"></i> Productos Vendidos
                    </h6>

                    <div class="table-responsive">
                        <table class="table table-dark table-striped table-hover table-bordered">
                            <thead>
                                <tr class="table-active">
                                    <th>Producto</th>
                                    <th class="text-center" width="10%">Cantidad</th>
                                    <th class="text-end" width="12%">Precio Unit.</th>
                                    <th class="text-end" width="12%">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="detalles-venta">
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="bi bi-inbox"></i> Cargando detalles...
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">SUBTOTAL:</td>
                                    <td class="text-end fw-bold" id="ver_subtotal">$0.00</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">IMPUESTO (21%):</td>
                                    <td class="text-end fw-bold" id="ver_impuesto">$0.00</td>
                                </tr>
                                <tr class="table-active">
                                    <td colspan="3" class="text-end fw-bold fs-5">TOTAL:</td>
                                    <td class="text-end fw-bold fs-5 text-primary" id="ver_total">$0.00</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="alert alert-secondary mt-3">
                        <strong><i class="bi bi-chat-left-text"></i> Observaciones:</strong><br>
                        <span id="ver_observaciones"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cerrar
                    </button>
                    <button type="button" class="btn btn-primary" onclick="window.print()">
                        <i class="bi bi-printer"></i> Imprimir
                    </button>
                </div>
            </div>
        </div>
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

        let filtroActual = 'todos';

        // FILTROS
        function aplicarFiltros() {
            const filas = document.querySelectorAll('#tabla-ventas tr');
            const buscador = document.getElementById('buscador').value.toLowerCase();
            let contadorVisible = 0;

            filas.forEach(fila => {
                const estado = fila.dataset.estado;
                const codigo = fila.dataset.codigo.toLowerCase();
                const cliente = fila.dataset.cliente.toLowerCase();

                const cumpleFiltroEstado = filtroActual === 'todos' || estado === filtroActual;
                const cumpleBuscador = codigo.includes(buscador) || cliente.includes(buscador);

                if (cumpleFiltroEstado && cumpleBuscador) {
                    fila.style.display = '';
                    contadorVisible++;
                } else {
                    fila.style.display = 'none';
                }
            });

            const contador = document.getElementById('contador');
            if (contador) {
                contador.innerHTML = `<i class="bi bi-info-circle"></i> <strong>Mostrando ${contadorVisible} ventas</strong>`;
            }
        }

        document.getElementById('filtro-estado').addEventListener('change', function() {
            filtroActual = this.value;
            aplicarFiltros();
        });

        document.getElementById('buscador').addEventListener('input', aplicarFiltros);

        // VER VENTA
        document.querySelectorAll('.ver-venta').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;

                fetch(`/ventas/${id}/json`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('ver_codigo').textContent = data.numero_factura;
                        document.getElementById('ver_cliente').textContent = data.cliente;
                        document.getElementById('ver_documento').textContent = data.cliente_documento || '—';
                        document.getElementById('ver_fecha').textContent = new Date(data.fecha_venta).toLocaleDateString('es-ES');
                        document.getElementById('ver_metodo').textContent = data.metodo_pago;
                        document.getElementById('ver_usuario').textContent = data.usuario?.name ?? '—';

                        const estadoSpan = document.getElementById('ver_estado');
                        estadoSpan.textContent = data.estado.charAt(0).toUpperCase() + data.estado.slice(1);
                        estadoSpan.className = 'badge ' + (data.estado === 'pendiente' ? 'bg-warning text-dark' : data.estado === 'completada' ? 'bg-success' : 'bg-secondary');

                        document.getElementById('ver_subtotal').textContent = '$' + parseFloat(data.subtotal).toFixed(2);
                        document.getElementById('ver_impuesto').textContent = '$' + (parseFloat(data.subtotal) * 0.21).toFixed(2);
                        document.getElementById('ver_total').textContent = '$' + parseFloat(data.total).toFixed(2);
                        document.getElementById('ver_observaciones').textContent = data.observaciones || 'Sin observaciones';

                        const tbody = document.getElementById('detalles-venta');
                        tbody.innerHTML = '';

                        if (data.detalles && data.detalles.length > 0) {
                            data.detalles.forEach(det => {
                                tbody.innerHTML += `
                                    <tr>
                                        <td>${det.producto?.nombre || '—'}</td>
                                        <td class="text-center">${det.cantidad}</td>
                                        <td class="text-end">$${parseFloat(det.precio_unitario).toFixed(2)}</td>
                                        <td class="text-end">$${parseFloat(det.subtotal).toFixed(2)}</td>
                                    </tr>
                                `;
                            });
                        }

                        const modal = new bootstrap.Modal(document.getElementById('modalVerVenta'));
                        modal.show();
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error al cargar venta');
                    });
            });
        });

        // CONFIRMAR VENTA
        document.querySelectorAll('.confirmar-venta').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                fetch(`/ventas/${id}/estado`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ estado: 'completada' })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('✅ Venta confirmada');
                        window.location.reload();
                    } else {
                        alert('❌ ' + data.message);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error al confirmar');
                });
            });
        });

        // ELIMINAR VENTA
        document.querySelectorAll('.eliminar-venta').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                if (confirm('¿Seguro que deseas eliminar esta venta?')) {
                    fetch(`/ventas/${id}/eliminar`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            window.location.reload();
                        } else {
                            alert('❌ ' + data.message);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error al eliminar');
                    });
                }
            });
        });

    </script>

</body>
</html>