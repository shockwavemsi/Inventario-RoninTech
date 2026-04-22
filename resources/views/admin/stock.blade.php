<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $config->nombre_empresa }} - Stock de Productos</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/stock.css') }}">

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
            <i class="bi bi-boxes"></i> Stock de Productos
        </h1>

        <div class="row mb-4" id="estadisticas-container">
            <!-- Generado por JavaScript -->
        </div>

        <div class="d-flex gap-2 mb-4">
            <select id="filtro-stock" class="form-select filtro-select">
                <option value="todos">Todos</option>
                <option value="normal">Normal</option>
                <option value="bajo">Stock Bajo</option>
                <option value="agotado">Agotados</option>
                <option value="exceso">Exceso</option>
            </select>

            <input type="text" id="buscador" class="form-control" style="max-width: 280px;" placeholder="🔍 Buscar...">
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><i class="bi bi-calendar"></i> Fecha</th>
                        <th><i class="bi bi-box"></i> Nombre</th>
                        <th><i class="bi bi-tag"></i> Categoría</th>
                        <th><i class="bi bi-shop"></i> Proveedor</th>
                        <th class="text-center"><i class="bi bi-stack"></i> Stock Actual</th>
                        <th class="text-center"><i class="bi bi-diagram-2"></i> Mín/Máx</th>
                        <th><i class="bi bi-info-circle"></i> Estado</th>
                    </tr>
                </thead>
                <tbody id="tabla-stock">
                    <!-- Generado por JavaScript -->
                </tbody>
            </table>
        </div>

        <p id="contador">
            <i class="bi bi-info-circle"></i> <strong>Mostrando 0 productos</strong>
        </p>

    </div>

    <!-- DATOS EN JSON PARA JAVASCRIPT -->
    <script>
        window.productosData = {!! json_encode($productos->map(function($p) {
            return [
                'id' => $p->id,
                'nombre' => $p->nombre,
                'categoria' => $p->categoria->nombre ?? '—',
                'proveedor' => $p->proveedor->nombre ?? '—',
                'stock_actual' => $p->stock_actual,
                'stock_minimo' => $p->stock_minimo,
                'stock_maximo' => $p->stock_maximo,
                'fecha_creacion' => $p->created_at->format('d/m/Y'),
            ];
        })->toArray()) !!};
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/stocks/stock.js') }}"></script>

</body>
</html>