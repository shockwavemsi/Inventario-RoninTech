<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $config->nombre_empresa }} - Devoluciones</title>

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
        <h1 class="mb-4">Devoluciones de Ventas</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <a href="{{ route('devoluciones.create') }}" class="btn btn-danger mb-3">
            + Nueva Devolución
        </a>

        <div class="d-flex justify-content-end gap-2 mb-3">
            <input type="text" id="buscador_codigo" class="form-control w-25" placeholder="🔍 Código devolución...">
            <input type="text" id="buscador_cliente" class="form-control w-25" placeholder="🔍 Cliente...">
        </div>

        <table class="table table-dark table-bordered">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Código</th>
                    <th>Cliente</th>
                    <th>Producto</th>
                    <th>Monto Devuelto</th>
                    <th class="text-center">Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-devoluciones">
                @foreach($devoluciones as $dev)
                    <tr>
                        <td>{{ $dev->fecha->format('d/m/Y') }}</td>
                        <td class="codigo">DEV-{{ str_pad($dev->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="cliente">
                            @if($dev->venta)
                                {{ $dev->venta->cliente }}
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @if($dev->detalles->first())
                                {{ $dev->detalles->first()->producto->nombre ?? '—' }}
                            @else
                                —
                            @endif
                        </td>
                        <td>${{ number_format($dev->total_devuelto, 2) }}</td>
                        <td class="text-center estado">
                            @if($dev->estado == 'completada')
                                <span class="badge bg-success">Completada</span>
                            @else
                                <span class="badge bg-warning text-dark">Pendiente</span>
                            @endif
                        </td>
                        <td>
                            <select class="form-select accion-devolucion" data-id="{{ $dev->id }}">
                                <option value="">Acciones</option>
                                <option value="ver">Ver</option>
                                @if($dev->estado === 'pendiente')
                                    <option value="completar">Completar</option>
                                @endif
                                <option value="eliminar">Eliminar</option>
                            </select>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p id="contador"><strong>Mostrando <span id="cantidad">{{ count($devoluciones) }}</span> devoluciones</strong></p>
    </div>

    <!-- MODAL VER DEVOLUCIÓN -->
    <div class="modal fade" id="modalVerDevolucion" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles de Devolución</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Código:</strong><br> <span id="ver_codigo"></span></p>
                            <p><strong>Cliente:</strong><br> <span id="ver_cliente"></span></p>
                            <p><strong>Usuario:</strong><br> <span id="ver_usuario"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Fecha:</strong><br> <span id="ver_fecha"></span></p>
                            <p><strong>Monto:</strong><br> $<span id="ver_monto"></span></p>
                            <p><strong>Estado:</strong><br> <span id="ver_estado" class="badge"></span></p>
                        </div>
                    </div>
                    <hr>
                    <h6><strong>Productos Devueltos</strong></h6>
                    <table class="table table-sm table-dark">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unit</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="ver_detalles">
                        </tbody>
                    </table>
                    <p><strong>Motivo:</strong><br> <span id="ver_motivo"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ACCIONES
        document.querySelectorAll('.accion-devolucion').forEach(select => {
            select.addEventListener('change', function () {
                const id = this.dataset.id;
                const accion = this.value;

                if (accion === 'ver') {
                    mostrarDevolucion(id);
                }
                if (accion === 'completar') {
                    if (confirm('¿Completar esta devolución?')) {
                        cambiarEstado(id, 'completada');
                    }
                }
                if (accion === 'eliminar') {
                    if (confirm('¿Seguro que deseas eliminar esta devolución?')) {
                        fetch(`/devoluciones/${id}/eliminar`, {
                            method: 'DELETE',
                            headers: { 
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        })
                        .then(res => {
                            if (res.ok) {
                                window.location.reload();
                            }
                        })
                        .catch(err => {
                            console.error('Error:', err);
                            alert('Error al eliminar');
                        });
                    }
                }
                this.value = '';
            });
        });

        // VER DEVOLUCIÓN
        function mostrarDevolucion(id) {
            fetch(`/devoluciones/${id}/json`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('ver_codigo').textContent = 'DEV-' + String(data.id).padStart(4, '0');
                    document.getElementById('ver_cliente').textContent = data.venta?.cliente ?? '—';
                    document.getElementById('ver_usuario').textContent = data.usuario?.name ?? '—';
                    document.getElementById('ver_fecha').textContent = new Date(data.fecha).toLocaleDateString('es-ES');
                    document.getElementById('ver_monto').textContent = parseFloat(data.total_devuelto).toFixed(2);
                    document.getElementById('ver_motivo').textContent = data.motivo ?? '—';

                    const estado = document.getElementById('ver_estado');
                    estado.textContent = data.estado.charAt(0).toUpperCase() + data.estado.slice(1);
                    estado.className = data.estado === 'completada' ? 'badge bg-success' : 'badge bg-warning text-dark';

                    // Tabla de detalles
                    const tbody = document.getElementById('ver_detalles');
                    tbody.innerHTML = '';
                    if (data.detalles && data.detalles.length > 0) {
                        data.detalles.forEach(detalle => {
                            const row = `<tr>
                                <td>${detalle.producto.nombre ?? '—'}</td>
                                <td>${detalle.cantidad}</td>
                                <td>$${parseFloat(detalle.precio_unitario).toFixed(2)}</td>
                                <td>$${parseFloat(detalle.subtotal).toFixed(2)}</td>
                            </tr>`;
                            tbody.innerHTML += row;
                        });
                    }

                    const modal = new bootstrap.Modal(document.getElementById('modalVerDevolucion'));
                    modal.show();
                });
        }

        // ✅ CAMBIAR ESTADO
        function cambiarEstado(id, estado) {
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
                } else {
                    alert('❌ ' + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error al actualizar');
            });
        }

        // BUSCADORES
        const buscadorCodigo = document.getElementById('buscador_codigo');
        const buscadorCliente = document.getElementById('buscador_cliente');
        const filas = document.querySelectorAll('#tabla-devoluciones tr');
        const contador = document.getElementById('cantidad');

        function filtrar() {
            const filtroCodigo = buscadorCodigo.value.toLowerCase();
            const filtroCliente = buscadorCliente.value.toLowerCase();
            let visibles = 0;

            filas.forEach(fila => {
                const codigo = fila.querySelector('.codigo')?.textContent.toLowerCase() || '';
                const cliente = fila.querySelector('.cliente')?.textContent.toLowerCase() || '';

                const coincideCodigo = codigo.includes(filtroCodigo) || filtroCodigo === '';
                const coincideCliente = cliente.includes(filtroCliente) || filtroCliente === '';

                if (coincideCodigo && coincideCliente) {
                    fila.style.display = '';
                    visibles++;
                } else {
                    fila.style.display = 'none';
                }
            });

            contador.textContent = visibles;
        }

        buscadorCodigo.addEventListener('keyup', filtrar);
        buscadorCliente.addEventListener('keyup', filtrar);
    </script>

</body>
</html>