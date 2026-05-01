<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ $config->nombre_empresa }} - Usuarios</title>
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/menu.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/compras.css') }}">
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

        <h1 class="mb-4">Usuarios</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <button class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#modalUsuario">
    Crear nuevo usuario
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
                <th><i class="bi bi-person"></i> Nombre</th>
                <th><i class="bi bi-envelope"></i> Email</th>
                <th><i class="bi bi-shield-lock"></i> Rol</th>
                <th style="width: 200px"><i class="bi bi-gear"></i> Acciones</th>
            </tr>
        </thead>

        <tbody id="tabla-usuarios">
            @foreach($usuarios as $u)
                <tr data-id="{{ $u->id }}" data-nombre="{{ $u->name }}">
                    
                    <td><i class="bi bi-hash"></i> {{ $u->id }}</td>

                    <td class="nombre">
                        <i class="bi bi-person-circle"></i> {{ $u->name }}
                    </td>

                    <td>{{ $u->email }}</td>

                    <td>
                        <span class="badge px-3 py-2
                            @if($u->role->name === 'admin') bg-danger
                            @elseif($u->role->name === 'usuario') bg-primary
                            @else bg-secondary @endif">
                            
                            <i class="bi 
                                @if($u->role->name === 'admin') bi-shield-fill-exclamation
                                @elseif($u->role->name === 'usuario') bi-person-check-fill
                                @else bi-person @endif
                                me-1"></i>

                            {{ ucfirst($u->role->name) }}
                        </span>
                    </td>

                    <td>
                        <select class="form-select form-select-sm accion-usuario bg-dark text-white border-secondary" data-id="{{ $u->id }}">
                            <option value="">⚙️ Acciones</option>
                            <option value="editar">✏️ Editar</option>
                            <option value="eliminar">🗑️ Eliminar</option>
                        </select>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

        <!-- CONTADOR -->
        <p id="contador"><strong>Mostrando {{ count($usuarios) }} usuarios</strong></p>

    </div>
    <!-- MODAL CREAR USUARIO -->
<div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Crear Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Tipo de Usuario</label>
                        <select name="role_id" class="form-select">
                            <option value="1">Administrador</option>
                            <option value="2">Usuario</option>
                        </select>
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

    <!-- JS ACCIONES + BUSCADOR + CONTADOR -->
    <script>
        // ACCIONES EDITAR / ELIMINAR
        document.querySelectorAll('.accion-usuario').forEach(select => {
            select.addEventListener('change', function () {
                const id = this.dataset.id;
                const accion = this.value;

                if (accion === 'editar') {
                    window.location.href = `/usuarios/${id}/editar`;
                }

                if (accion === 'eliminar') {
                    if (confirm('¿Seguro que deseas eliminar este usuario?')) {
                        fetch(`/usuarios/${id}/eliminar`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(() => window.location.reload());
                    }
                }
            });
        });

        // BUSCADOR + CONTADOR
        const buscador = document.getElementById('buscador');
        const filas = document.querySelectorAll('#tabla-usuarios tr');
        const contador = document.getElementById('contador');
        const totalUsuarios = filas.length;

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

            contador.innerHTML = `<strong>Mostrando ${visibles} de ${totalUsuarios} usuarios</strong>`;
        });
    </script>

</body>
</html>
