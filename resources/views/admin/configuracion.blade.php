<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $config->nombre_empresa }} - Configuración</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        /* SIDEBAR */
        .sidebar {
            width: 240px;
            height: 100vh;
            background: #343a40;
            color: #fff;
            position: fixed;
            left: 0;
            top: 0;
            padding-top: 20px;
        }

        .sidebar h3 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #ddd;
            text-decoration: none;
            font-size: 15px;
        }

        .sidebar a:hover {
            background: #495057;
            color: #fff;
        }

        .sidebar .submenu {
            padding-left: 30px;
            font-size: 14px;
        }

        /* CONTENIDO */
        .content {
            margin-left: 260px;
            padding: 30px;
        }
    </style>
</head>

<body>

    <!-- SIDEBAR (MISMO QUE EL DASHBOARD) -->
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

        <a href="{{ route('logout') }}" class="mt-4">Cerrar sesión</a>
    </div>

    <!-- CONTENIDO -->
    <div class="content">

        <h1 class="mb-4">Configuración del Sistema</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('config.update') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Nombre de la Empresa</label>
                <input type="text" name="nombre_empresa" class="form-control" value="{{ $config->nombre_empresa }}">
            </div>

            <div class="mb-3">
                <label>RUC</label>
                <input type="text" name="ruc" class="form-control" value="{{ $config->ruc }}">
            </div>

            <div class="mb-3">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="{{ $config->telefono }}">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ $config->email }}">
            </div>

            <div class="mb-3">
                <label>Dirección</label>
                <textarea name="direccion" class="form-control">{{ $config->direccion }}</textarea>
            </div>

            <div class="mb-3">
                <label>Logo (URL o ruta)</label>
                <input type="text" name="logo" class="form-control" value="{{ $config->logo }}">
            </div>

            <div class="mb-3">
                <label>Impuesto (%)</label>
                <input type="number" step="0.01" name="impuesto_porcentaje" class="form-control" value="{{ $config->impuesto_porcentaje }}">
            </div>

            <div class="mb-3">
                <label>Moneda</label>
                <input type="text" name="moneda" class="form-control" value="{{ $config->moneda }}">
            </div>

            <div class="mb-3">
                <label>Formato de Factura</label>
                <input type="text" name="formato_factura" class="form-control" value="{{ $config->formato_factura }}">
            </div>

            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>

    </div>

</body>
</html>

