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

    <style>
        /* Override Bootstrap para tema cyberpunk */
        .table {
            color: #f0f0f0;
        }

        .table-dark {
            background-color: rgba(15, 15, 20, 0.9) !important;
        }

        .table-dark thead th {
            border-color: #e63946 !important;
            background-color: rgba(230, 57, 70, 0.15) !important;
        }

        .table-dark tbody + thead th {
            border-color: #e63946 !important;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(230, 57, 70, 0.1) !important;
            color: #f0f0f0;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(20, 20, 25, 0.3) !important;
        }

        .table-striped tbody tr:nth-of-type(even) {
            background-color: transparent;
        }

        .table-bordered {
            border-color: rgba(230, 57, 70, 0.2) !important;
        }

        .table-bordered th,
        .table-bordered td {
            border-color: rgba(230, 57, 70, 0.2) !important;
        }
    </style>

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
            <i class="bi bi-boxes"></i> Stock de Productos
        </h1>

        <div class="row mb-4" id="estadisticas-container">
            <!-- Generado por JavaScript -->
        </div>

        <div class="d-flex gap-2 mb-4">
            <select id="filtro-stock" class="form-select form-select-sm">
                <option value="todos">Todos</option>
                <option value="normal">Normal</option>
                <option value="bajo">Stock Bajo</option>
                <option value="agotado">Agotados</option>
                <option value="exceso">Exceso</option>
            </select>
            <input type="text" id="buscador" class="form-control form-control-sm" placeholder="🔍 Buscar...">
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover table-bordered">
                <thead>
                    <tr class="table-active">
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

    </script>

    <script src="{{ asset('js/stocks/stock.js') }}"></script>

</body>
</html>