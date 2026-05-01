<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $config->nombre_empresa }} - Devoluciones</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/compras.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/menu.js') }}"></script>

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
            <i class="bi bi-arrow-counterclockwise"></i> Devoluciones de Ventas
        </h1>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        <!-- TARJETAS DE ESTADÍSTICAS -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-arrow-counterclockwise" style="font-size: 2.5rem; color: var(--neon-red); opacity: 0.7;"></i>
                        <div>
                            <div class="stat-label">Total Devoluciones</div>
                            <div class="stat-value">{{ count($devoluciones) }}</div>
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
                            <div class="stat-value">{{ $devoluciones->where('estado', 'pendiente')->count() }}</div>
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
                            <div class="stat-value">{{ $devoluciones->where('estado', 'completada')->count() }}</div>
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
                            <div class="stat-value">${{ number_format($devoluciones->sum('total_devuelto'), 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTROS Y BUSCADOR -->
        <div class="d-flex justify-content-between align-items-center mb-4 gap-3">
            <a href="{{ route('devoluciones.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nueva Devolución
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

        <!-- TABLA PRINCIPAL -->
        <div class="table-responsive">
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th><i class="bi bi-receipt"></i> Código</th>
                        <th><i class="bi bi-calendar"></i> Fecha</th>
                        <th><i class="bi bi-person"></i> Cliente</th>
                        <th><i class="bi bi-box"></i> Producto</th>
                        <th><i class="bi bi-cash-coin"></i> Monto</th>
                        <th><i class="bi bi-info-circle"></i> Estado</th>
                        <th style="width: 200px"><i class="bi bi-gear"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-devoluciones">
                    @foreach($devoluciones as $dev)
                        <tr data-estado="{{ $dev->estado }}" data-codigo="DEV-{{ str_pad($dev->id, 4, '0', STR_PAD_LEFT) }}" data-cliente="{{ $dev->venta->cliente ?? '' }}">
                            <td class="codigo">
                                <i class="bi bi-receipt"></i> DEV-{{ str_pad($dev->id, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td>{{ $dev->fecha->format('d/m/Y') }}</td>
                            <td class="cliente">{{ $dev->venta->cliente ?? '—' }}</td>
                            <td>{{ $dev->detalles->first()->producto->nombre ?? '—' }}</td>
                            <td><strong>${{ number_format($dev->total_devuelto, 2) }}</strong></td>
                            <td>
                                <span class="badge estado-badge px-3 py-2
                                    @if($dev->estado === 'pendiente') bg-warning text-dark
                                    @elseif($dev->estado === 'completada') bg-success
                                    @else bg-secondary @endif">
                                    <i class="bi 
                                        @if($dev->estado === 'pendiente') bi-clock-history
                                        @elseif($dev->estado === 'completada') bi-check-circle-fill
                                        @else bi-question-circle @endif
                                        me-1"></i>
                                    {{ ucfirst($dev->estado) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-info ver-devolucion" data-id="{{ $dev->id }}" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @if($dev->estado === 'pendiente')
                                        <button type="button" class="btn btn-success cambiar-estado" data-id="{{ $dev->id }}" data-estado="completada" title="Completar">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-danger eliminar-devolucion" data-id="{{ $dev->id }}" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <br>

        <p id="contador">
            <i class="bi bi-info-circle"></i> <strong>Mostrando {{ count($devoluciones) }} devoluciones</strong>
        </p>

    </div>

    <!-- MODAL VER DEVOLUCIÓN -->
    <div class="modal fade" id="modalVerDevolucion" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-receipt"></i> Detalles de la Devolución
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
                                    <p class="mb-0">
                                        <strong>Fecha:</strong><br>
                                        <span id="ver_fecha"></span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-3">
                                        <i class="bi bi-dash-circle"></i> Estado y Totales
                                    </h6>
                                    <p class="mb-2">
                                        <strong>Estado:</strong><br>
                                        <span id="ver_estado" class="badge"></span>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Monto Devuelto:</strong><br>
                                        <span id="ver_monto" style="color: var(--neon-red); font-weight: bold;">$0.00</span>
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
                        <i class="bi bi-box-seam"></i> Productos Devueltos
                    </h6>

                    <div class="table-responsive">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center" width="12%">Cantidad</th>
                                    <th class="text-end" width="15%">Precio Unit.</th>
                                    <th class="text-end" width="15%">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="detalles-devolucion">
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="bi bi-inbox"></i> Seleccione una devolución para ver los detalles
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">TOTAL:</td>
                                    <td class="text-end fw-bold text-primary" id="ver_total">$0.00</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="alert alert-secondary mt-3">
                        <strong><i class="bi bi-chat-left-text"></i> Motivo:</strong><br>
                        <span id="ver_motivo"></span>
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

        // FILTROS Y BÚSQUEDA
        let filtroActual = 'todos';

        function aplicarFiltros() {
            const filas = document.querySelectorAll('#tabla-devoluciones tr');
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
                contador.innerHTML = `<i class="bi bi-info-circle"></i> <strong>Mostrando ${contadorVisible} devoluciones</strong>`;
            }
        }

        document.getElementById('filtro-estado').addEventListener('change', function() {
            filtroActual = this.value;
            aplicarFiltros();
        });

        document.getElementById('buscador').addEventListener('input', aplicarFiltros);

        // VER DEVOLUCIÓN
        document.querySelectorAll('.ver-devolucion').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;

                fetch(`/devoluciones/${id}/json`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('ver_codigo').textContent = 'DEV-' + String(data.id).padStart(4, '0');
                        document.getElementById('ver_cliente').textContent = data.venta?.cliente ?? '—';
                        document.getElementById('ver_fecha').textContent = new Date(data.fecha).toLocaleDateString('es-ES');
                        document.getElementById('ver_monto').textContent = '$' + parseFloat(data.total_devuelto).toFixed(2);
                        document.getElementById('ver_usuario').textContent = data.usuario?.name ?? '—';
                        document.getElementById('ver_motivo').textContent = data.motivo ?? '—';

                        const estado = document.getElementById('ver_estado');
                        estado.textContent = data.estado.charAt(0).toUpperCase() + data.estado.slice(1);
                        estado.className = data.estado === 'completada' ? 'badge bg-success' : 'badge bg-warning text-dark';

                        const tbody = document.getElementById('detalles-devolucion');
                        tbody.innerHTML = '';
                        let total = 0;

                        if (data.detalles && data.detalles.length > 0) {
                            data.detalles.forEach(det => {
                                const subtotal = det.cantidad * det.precio_unitario;
                                total += subtotal;
                                const row = `
                                    <tr>
                                        <td>${det.producto?.nombre || '—'}</td>
                                        <td class="text-center">${det.cantidad}</td>
                                        <td class="text-end">$${parseFloat(det.precio_unitario).toFixed(2)}</td>
                                        <td class="text-end">$${subtotal.toFixed(2)}</td>
                                    </tr>
                                `;
                                tbody.innerHTML += row;
                            });
                        }

                        document.getElementById('ver_total').textContent = '$' + total.toFixed(2);

                        const modal = new bootstrap.Modal(document.getElementById('modalVerDevolucion'));
                        modal.show();
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error al cargar devolución');
                    });
            });
        });

        // CAMBIAR ESTADO
        document.querySelectorAll('.cambiar-estado').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const estado = this.dataset.estado;

                if (confirm('¿Completar esta devolución?')) {
                    fetch(`/devoluciones/${id}/estado`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ estado: estado })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert('✅ ' + data.message);
                            window.location.reload();
                        }
                    });
                }
            });
        });

        // ELIMINAR DEVOLUCIÓN
        document.querySelectorAll('.eliminar-devolucion').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;

                if (confirm('¿Seguro que deseas eliminar esta devolución?')) {
                    fetch(`/devoluciones/${id}/eliminar`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(res => {
                        if (res.ok) {
                            alert('✅ Devolución eliminada');
                            window.location.reload();
                        }
                    });
                }
            });
        });

    </script>

</body>
</html>