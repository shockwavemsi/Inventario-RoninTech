<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SIS-INVENTARIOS - Panel Admin</title>
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        /* CONTENIDO */
        .content {
            margin-left: 260px;
            padding: 30px;
        }

        .card-box {
            padding: 20px;
            border-radius: 10px;
            color: #fff;
            transition: 0.2s;
        }

        .card-box:hover {
            transform: scale(1.03);
            cursor: pointer;
            opacity: 0.9;
        }

        .bg-blue { background: #007bff; }
        .bg-green { background: #28a745; }
        .bg-orange { background: #fd7e14; }
        .bg-red { background: #dc3545; }
        .bg-purple { background: #6f42c1; }
        .bg-darkblue { background: #343a40; }

        .icon-box {
            font-size: 40px;
            opacity: 0.7;
        }

        a.block-link {
            text-decoration: none;
        }
    </style>
    <script src="{{ asset('js/menu.js') }}"></script>
</head>

<body>

    <div class="sidebar">
    <h3>{{ $config->nombre_empresa }}</h3>
    <div id="menu-contenedor"></div>
    <a href="{{ route('logout') }}" class="mt-4">Cerrar sesión</a>
</div>
    

    <!-- CONTENIDO -->
    <div class="content">
        <h1 class="mb-4">Dashboard</h1>

        <div class="row g-4">

            <!-- Órdenes de Compra -->
            <div class="col-md-4">
                <a href="/ordenes" class="block-link">
                    <div class="card-box bg-blue">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>Órdenes de Compra</h4>
                                <h2>3</h2>
                            </div>
                            <div class="icon-box">📦</div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Compras Recibidas -->
            <div class="col-md-4">
                <a href="/recibidos" class="block-link">
                    <div class="card-box bg-green">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>Compras Recibidas</h4>
                                <h2>2</h2>
                            </div>
                            <div class="icon-box">📥</div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Devoluciones -->
            <div class="col-md-4">
                <a href="/devoluciones" class="block-link">
                    <div class="card-box bg-orange">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>Devoluciones</h4>
                                <h2>1</h2>
                            </div>
                            <div class="icon-box">↩️</div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Ventas -->
            <div class="col-md-4">
                <a href="/ventas" class="block-link">
                    <div class="card-box bg-red">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>Ventas</h4>
                                <h2>1</h2>
                            </div>
                            <div class="icon-box">💰</div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Proveedores -->
            <div class="col-md-4">
                <a href="/proveedores" class="block-link">
                    <div class="card-box bg-purple">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>Proveedores</h4>
                                <h2>1</h2>
                            </div>
                            <div class="icon-box">🏭</div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Productos -->
            <div class="col-md-4">
                <a href="/productos" class="block-link">
                    <div class="card-box bg-darkblue">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>Productos</h4>
                                <h2>2</h2>
                            </div>
                            <div class="icon-box">📦</div>
                        </div>
                    </div>
                </a>
            </div>

        </div>

    </div>

</body>
</html>

