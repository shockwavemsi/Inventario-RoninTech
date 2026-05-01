<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ $config->nombre_empresa }} - Proveedores</title>
    

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/compras.css') }}">
    <script src="{{ asset('js/menu.js') }}"></script>
</head>

<body>
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

    <!-- CONTENIDO -->
    <div class="content">

        <h1 class="mb-4">Proveedores</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- BOTÓN PARA ABRIR EL MODAL -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalProveedor">
            Crear nuevo proveedor
        </button>

        <!-- BUSCADOR -->
        <div class="d-flex justify-content-end mb-3">
            <input type="text" id="buscador" class="form-control w-25" placeholder="Buscar por nombre...">
        </div>

        <!-- TABLA -->
        <div class="table-responsive">
    <table class="table table-dark table-hover">
        <thead>
            <tr>
                <th><i class="bi bi-hash"></i> ID</th>
                <th><i class="bi bi-calendar"></i> Fecha</th>
                <th><i class="bi bi-shop"></i> Nombre</th>
                <th><i class="bi bi-person"></i> Contacto</th>
                <th><i class="bi bi-telephone"></i> Teléfono</th>
                <th><i class="bi bi-envelope"></i> Email</th>
                <th><i class="bi bi-geo-alt"></i> Dirección</th>
                <th><i class="bi bi-info-circle"></i> Estado</th>
                <th style="width: 200px"><i class="bi bi-gear"></i> Acciones</th>
            </tr>
        </thead>

        <tbody id="tabla-proveedores">
            @foreach($proveedores as $p)
                <tr data-estado="{{ $p->activo ? 'activo' : 'inactivo' }}" 
                    data-nombre="{{ $p->nombre }}">
                    
                    <td><i class="bi bi-hash"></i> {{ $p->id }}</td>

                    <td>{{ $p->created_at->format('d/m/Y') }}</td>

                    <td class="nombre">
                        <i class="bi bi-building"></i> {{ $p->nombre }}
                    </td>

                    <td>{{ $p->contacto_nombre }}</td>
                    <td>{{ $p->contacto_telefono }}</td>
                    <td>{{ $p->email }}</td>
                    <td>{{ $p->direccion }}</td>

                    <td>
                        <span class="badge estado-badge px-3 py-2
                            @if($p->activo) bg-success
                            @else bg-secondary @endif">
                            
                            <i class="bi 
                                @if($p->activo) bi-check-circle-fill
                                @else bi-x-circle @endif
                                me-1"></i>

                            {{ $p->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>

                    <td>
                        <select class="form-select form-select-sm accion-proveedor bg-dark text-white border-secondary" data-id="{{ $p->id }}">
                            <option value="">⚙️ Acciones</option>
                            <option value="ver">👁️ Ver</option>
                            <option value="editar">✏️ Editar</option>
                            <option value="eliminar">🗑️ Eliminar</option>
                        </select>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

        <p id="contador"><strong>Mostrando {{ count($proveedores) }} proveedores</strong></p>

    </div>

    <!-- MODAL AGREGAR PROVEEDOR -->
    <div class="modal fade" id="modalProveedor" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="{{ route('proveedores.store') }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Proveedor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Dirección</label>
                            <textarea name="direccion" class="form-control"></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Persona de Contacto</label>
                            <input type="text" name="contacto_nombre" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label># de Contacto</label>
                            <input type="text" name="contacto_telefono" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Estado</label>
                            <select name="activo" class="form-select">
                                <option value="1">Activo</option>
                                <option value="0">Desactivado</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Fecha</label>
                            <input type="date" name="created_at" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- MODAL VER PROVEEDOR -->
    <div class="modal fade" id="modalVerProveedor" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Información de Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <p><strong>Nombre:</strong><br> <span id="ver_nombre"></span></p>

                    <p><strong>Dirección:</strong><br> <span id="ver_direccion"></span></p>

                    <p><strong>Persona de Contacto:</strong><br> <span id="ver_contacto"></span></p>

                    <p><strong># Contacto:</strong><br> <span id="ver_telefono"></span></p>

                    <p><strong>Email:</strong><br> <span id="ver_email"></span></p>

                    <p><strong>Estado:</strong><br>
                        <span id="ver_estado" class="badge"></span>
                    </p>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>

    <!-- JS ACCIONES + BUSCADOR + CONTADOR + VER -->
    <script>
        // ACCIONES
        document.querySelectorAll('.accion-proveedor').forEach(select => {
            select.addEventListener('change', function () {
                const id = this.dataset.id;
                const accion = this.value;

                if (accion === 'ver') {
                    mostrarProveedor(id);
                }

                if (accion === 'editar') {
                    window.location.href = `/proveedores/${id}/editar`;
                }

                if (accion === 'eliminar') {
    console.log('Eliminando proveedor:', id);

    if (confirm('¿Seguro que deseas eliminar este proveedor?')) {
        fetch(`/proveedores/${id}`, { // 👈 ruta correcta REST
            method: 'DELETE',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => {
            console.log('Response:', res.status);
            if (res.ok) {
                console.log('Recargando página...');
                setTimeout(() => window.location.reload(), 500);
            } else {
                alert('Error al eliminar proveedor');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Error en la solicitud');
        });
    }
}
            });
        });

        // FUNCIÓN PARA VER PROVEEDOR
        function mostrarProveedor(id) {
            fetch(`/proveedores/${id}/json`)
                .then(res => res.json())
                .then(data => {

                    document.getElementById('ver_nombre').textContent = data.nombre;
                    document.getElementById('ver_direccion').textContent = data.direccion ?? '—';
                    document.getElementById('ver_contacto').textContent = data.contacto_nombre ?? '—';
                    document.getElementById('ver_telefono').textContent = data.contacto_telefono ?? '—';
                    document.getElementById('ver_email').textContent = data.email ?? '—';

                    const estado = document.getElementById('ver_estado');
                    estado.textContent = data.activo ? 'Activo' : 'Inactivo';
                    estado.className = data.activo ? 'badge bg-success' : 'badge bg-danger';

                    const modal = new bootstrap.Modal(document.getElementById('modalVerProveedor'));
                    modal.show();
                });
        }

        // BUSCADOR + CONTADOR
        const buscador = document.getElementById('buscador');
        const filas = document.querySelectorAll('#tabla-proveedores tr');
        const contador = document.getElementById('contador');
        const total = filas.length;

        buscador.addEventListener('keyup', function () {
            const filtro = this.value.toLowerCase();
            let visibles = 0;

            filas.forEach(fila => {
                const nombre = fila.querySelector('.nombre').textContent.toLowerCase();

                if (nombre.includes(filtro)) {
                    fila.style.display = '';
                    visibles++;
                } else {
                    fila.style.display = 'none';
                }
            });

            contador.innerHTML = `<strong>Mostrando ${visibles} de ${total} proveedores</strong>`;
        });
    </script>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>


