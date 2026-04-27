<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ $config->nombre_empresa }} - Usuarios</title>
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="{{ asset('js/menu.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/compras.css') }}">
</head>

<body>

    <div class="sidebar">
        <h3>{{ $config->nombre_empresa }}</h3>
        <div id="menu-contenedor"></div>
        <a href="{{ route('logout') }}" class="mt-4">Cerrar sesión</a>
    </div>
    

    <!-- CONTENIDO -->
    <div class="content">

        <h1 class="mb-4">Usuarios</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('usuarios.create') }}" class="btn btn-primary mb-3">Crear nuevo usuario</a>

        <!-- BUSCADOR -->
        <div class="d-flex justify-content-end mb-3">
            <input type="text" id="buscador" class="form-control w-25" placeholder="Buscar por nombre...">
        </div>

        <!-- TABLA -->
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Tipo de Usuario</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody id="tabla-usuarios">
                @foreach($usuarios as $u)
                    <tr>
                        <td>{{ $u->id }}</td>
                        <td class="nombre">{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->role->name }}</td>
                        <td>
                            <select class="form-select accion-usuario" data-id="{{ $u->id }}">
                                <option value="">Acciones</option>
                                <option value="editar">Editar</option>
                                <option value="eliminar">Eliminar</option>
                            </select>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- CONTADOR -->
        <p id="contador"><strong>Mostrando {{ count($usuarios) }} usuarios</strong></p>

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
