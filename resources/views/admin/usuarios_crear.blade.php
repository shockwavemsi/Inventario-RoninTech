<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $config->nombre_empresa }} - Crear Usuario</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body { background: #f4f6f9; font-family: Arial; }
        .sidebar {
            width: 240px; height: 100vh; background: #343a40; color: #fff;
            position: fixed; left: 0; top: 0; padding-top: 20px;
        }
        .sidebar a { display: block; padding: 12px 20px; color: #ddd; text-decoration: none; }
        .sidebar a:hover { background: #495057; color: #fff; }
        .submenu { padding-left: 30px; }
        .content { margin-left: 260px; padding: 30px; }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h3>{{ $config->nombre_empresa }}</h3>

        <a href="/admin">Dashboard</a>
        <a href="#">Compras</a>
        <a href="#">Recibidos</a>
        <a href="#">Devoluciones</a>
        <a href="#">Stocks</a>
        <a href="#">Ventas</a>

        <a href="#">Mantenimiento</a>
        <div class="submenu">
            <a href="#">Proveedores</a>
            <a href="#">Productos</a>
            <a href="/usuarios">Usuarios</a>
            <a href="/configuracion">Configuración</a>
        </div>

        <a href="{{ route('logout') }}">Cerrar sesión</a>
    </div>

    <!-- CONTENIDO -->
    <div class="content">

        <h1 class="mb-4">Crear Usuario</h1>

        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf

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

            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>

    </div>

</body>
</html>
