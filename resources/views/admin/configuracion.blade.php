<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $config->nombre_empresa }} - Configuración</title>
    <link rel="stylesheet" href="{{ asset('css/main_menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="{{ asset('js/menu.js') }}"></script>  
    
</head>

<body>

    <div class="sidebar">
        <h3>{{ $config->nombre_empresa }}</h3>
        <div id="menu-contenedor"></div>
        <a href="{{ route('logout') }}" class="mt-4">Cerrar sesión</a>
    </div>

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

