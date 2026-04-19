<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $config->nombre_empresa }} - Órdenes de Compra</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/compras.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/menu.js') }}"></script>
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

        <h1>
            <i class="bi bi-cart3"></i> Órdenes de Compra
        </h1>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-cart-check" style="font-size: 2.5rem; color: var(--neon-red); opacity: 0.7;"></i>
                        <div>
                            <div class="stat-label">Total de Compras</div>
                            <div class="stat-value">{{ count($compras) }}</div>
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
                            <div class="stat-value">{{ $compras->where('estado', 'pendiente')->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-check-circle-fill" style="font-size: 2.5rem; color: var(--neon-red); opacity: 0.7;"></i>
                        <div>
                            <div class="stat-label">Recibidos</div>
                            <div class="stat-value">{{ $compras->where('estado', 'recibido')->count() }}</div>
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
                            <div class="stat-value">${{ number_format($compras->sum('total'), 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4 gap-3">
            <a href="{{ route('compras.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nueva Orden
            </a>

            <div class="d-flex gap-2" style="flex: 1; max-width: 600px;">
                <select id="filtro-estado" class="form-select filtro-select">
                    <option value="todos">Todos</option>
                    <option value="recibido">Recibidos</option>
                    <option value="pendiente">Pendientes</option>
                </select>
                <input type="text" id="buscador" class="form-control" placeholder="🔍 Buscar por código o proveedor...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><i class="bi bi-barcode"></i> Código</th>
                        <th><i class="bi bi-calendar"></i> Fecha</th>
                        <th><i class="bi bi-shop"></i> Proveedor</th>
                        <th><i class="bi bi-cash-coin"></i> Total</th>
                        <th><i class="bi bi-info-circle"></i> Estado</th>
                        <th><i class="bi bi-person"></i> Usuario</th>
                        <th style="width: 150px"><i class="bi bi-gear"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-compras">
                    @foreach($compras as $c)
                        <tr data-estado="{{ $c->estado }}" data-codigo="{{ $c->numero_factura }}" data-proveedor="{{ $c->proveedor->nombre ?? '' }}">
                            <td class="codigo">
                                <i class="bi bi-receipt"></i> {{ $c->numero_factura }}
                            </td>
                            <td>{{ $c->created_at->format('d/m/Y') }}</td>
                            <td class="proveedor">{{ $c->proveedor->nombre ?? '—' }}</td>
                            <td><strong>${{ number_format($c->total, 2) }}</strong></td>
                            <td>
                                <span class="badge estado-badge px-3 py-2
                                    @if($c->estado === 'pendiente') bg-warning text-dark
                                    @elseif($c->estado === 'recibido') bg-success
                                    @else bg-secondary @endif">
                                    <i class="bi 
                                        @if($c->estado === 'pendiente') bi-clock-history
                                        @elseif($c->estado === 'recibido') bi-check-circle-fill
                                        @else bi-question-circle @endif
                                        me-1"></i>
                                    {{ ucfirst($c->estado) }}
                                </span>
                            </td>
                            <td>{{ $c->usuario->name ?? '—' }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-info ver-compra" 
                                            data-id="{{ $c->id }}" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    @if($c->estado === 'pendiente')
                                        <button type="button" class="btn btn-success cambiar-estado" 
                                                data-id="{{ $c->id }}" data-estado="recibido" 
                                                title="Recibir compra">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    @endif

                                    <button type="button" class="btn btn-danger eliminar-compra" 
                                            data-id="{{ $c->id }}" title="Eliminar compra">
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
            <i class="bi bi-info-circle"></i> <strong>Mostrando {{ count($compras) }} compras</strong>
        </p>

    </div>

    <div class="modal fade" id="modalVerCompra" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-receipt"></i> Detalles de la Orden de Compra
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
                                        <strong>Proveedor:</strong><br>
                                        <span id="ver_proveedor"></span>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Fecha Pedido:</strong><br>
                                        <span id="ver_fecha_pedido"></span>
                                    </p>
                                    <p class="mb-0">
                                        <strong>Fecha Entrega Esperada:</strong><br>
                                        <span id="ver_fecha_entrega"></span>
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
                                        <strong>Usuario:</strong><br>
                                        <span id="ver_usuario"></span>
                                    </p>
                                    <p class="mb-0">
                                        <strong>Fecha Creación:</strong><br>
                                        <span id="ver_created_at"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6 class="mb-3">
                        <i class="bi bi-box-seam"></i> Productos Comprados
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Producto</th>
                                    <th>Marca/Modelo</th>
                                    <th class="text-center" width="10%">Cantidad</th>
                                    <th class="text-end" width="12%">Precio Unit.</th>
                                    <th class="text-end" width="12%">Descuento</th>
                                    <th class="text-end" width="12%">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="detalles-compra">
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <i class="bi bi-inbox"></i> Seleccione una compra para ver los detalles
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="6" class="text-end fw-bold">SUBTOTAL:</td>
                                    <td class="text-end fw-bold" id="ver_subtotal">$0.00</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-end fw-bold text-danger">DESCUENTO:</td>
                                    <td class="text-end fw-bold text-danger" id="ver_descuento">$0.00</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-end fw-bold">IMPUESTO (21%):</td>
                                    <td class="text-end fw-bold" id="ver_impuesto">$0.00</td>
                                </tr>
                                <tr class="table-active">
                                    <td colspan="6" class="text-end fw-bold fs-5">TOTAL:</td>
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
    <script src="{{ asset('js/compras/compras.js') }}"></script>
    <script src="{{ asset('js/compras/compras-ver.js') }}"></script>
    <script src="{{ asset('js/compras/compras-estado.js') }}"></script>
    <script src="{{ asset('js/compras/compras-eliminar.js') }}"></script>
    <script src="{{ asset('js/compras/compras-buscador.js') }}"></script>
    <script src="{{ asset('js/compras/compras-crear.js') }}"></script>
    <script src="{{ asset('js/compras/compras-actualizar.js') }}"></script>
    <script src="{{ asset('js/compras/compras-stats.js') }}"></script>
    <script src="{{ asset('js/compras/model-fix.js') }}"></script>

    <script>
        let filtroActual = 'todos';

        function aplicarFiltros() {
            const filas = document.querySelectorAll('#tabla-compras tr');
            const buscador = document.getElementById('buscador').value.toLowerCase();
            let contadorVisible = 0;

            filas.forEach(fila => {
                const estado = fila.dataset.estado;
                const codigo = fila.dataset.codigo.toLowerCase();
                const proveedor = fila.dataset.proveedor.toLowerCase();

                const cumpleFiltroEstado = filtroActual === 'todos' || estado === filtroActual;
                const cumpleBuscador = codigo.includes(buscador) || proveedor.includes(buscador);

                if (cumpleFiltroEstado && cumpleBuscador) {
                    fila.style.display = '';
                    contadorVisible++;
                } else {
                    fila.style.display = 'none';
                }
            });

            const contador = document.getElementById('contador');
            if (contador) {
                contador.innerHTML = `<i class="bi bi-info-circle"></i> <strong>Mostrando ${contadorVisible} compras</strong>`;
            }
        }

        document.getElementById('filtro-estado').addEventListener('change', function() {
            filtroActual = this.value;
            aplicarFiltros();
        });

        document.getElementById('buscador').addEventListener('input', aplicarFiltros);
    </script>

</body>
</html>