<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SIS-INVENTARIOS - Panel Admin</title>
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/compras.css') }}">
    
    <!-- Chart.js para gráficas -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        /* Colores originales */
        .bg-blue { background: #007bff; }
        .bg-green { background: #28a745; }
        .bg-orange { background: #fd7e14; }
        .bg-red { background: #dc3545; }
        .bg-purple { background: #6f42c1; }
        .bg-darkblue { background: #343a40; }
        .bg-cyan { background: #17a2b8; }
        .bg-pink { background: #e83e8c; }

        .icon-box {
            font-size: 40px;
            opacity: 0.7;
        }

        a.block-link {
            text-decoration: none;
        }

        /* Tarjeta de estadística pequeña */
        .stat-mini-card {
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 10px;
            text-align: center;
        }

        .trend-up { color: #00ff88; }
        .trend-down { color: #ff4444; }

        /* Gráficas */
        .chart-container {
            background: #1a1a2e;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        }

        .chart-title {
            color: #fff;
            font-size: 1.1rem;
            margin-bottom: 15px;
            border-left: 4px solid #e63946;
            padding-left: 12px;
        }

        /* Tabla de actividades */
        .activity-list {
            background: #1a1a2e;
            border-radius: 15px;
            padding: 0;
            overflow: hidden;
        }

        .activity-item {
            padding: 12px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: 0.2s;
        }

        .activity-item:hover {
            background: rgba(230, 57, 70, 0.1);
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .activity-content {
            flex: 1;
        }

        .activity-text {
            color: #fff;
            margin: 0;
            font-size: 0.9rem;
        }

        .activity-time {
            color: #888;
            font-size: 0.75rem;
        }

        /* Alerta stock crítico */
        .alert-critical {
            background: linear-gradient(135deg, #dc3545, #c82333);
            border: none;
            border-radius: 10px;
        }

        /* Badge de porcentaje */
        .badge-percent {
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 20px;
        }
    </style>
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
        
        <!-- ENCABEZADO CON FECHA -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">📊 Dashboard</h1>
            <div class="text-end">
                
            </div>
        </div>
        <!-- ========================================== -->
        <!-- ALERTA DE STOCK CRÍTICO -->
        <!-- ========================================== -->
        @php
            use App\Models\Producto;
            $productosCriticos = Producto::whereRaw('stock_actual <= stock_minimo')
                ->where('activo', true)
                ->limit(5)
                ->get();
        @endphp

        @if($productosCriticos->count() > 0)
        <div class="alert alert-critical alert-dismissible fade show mb-4 shadow-lg" role="alert">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                <div class="flex-grow-1">
                    <strong class="fs-5">⚠️ ¡Atención! Stock crítico</strong>
                    <div class="row mt-2">
                        @foreach($productosCriticos as $producto)
                        <div class="col-md-4">
                            <small>
                                <strong>{{ $producto->nombre }}</strong><br>
                                Stock: {{ $producto->stock_actual }} / Mínimo: {{ $producto->stock_minimo }}
                            </small>
                        </div>
                        @endforeach
                    </div>
                </div>
                <a href="{{ route('productos.index') }}" class="btn btn-sm btn-light">
                    <i class="bi bi-eye"></i> Ver todos
                </a>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        <!-- ========================================== -->
        <!-- KPI CARDS PRINCIPALES (6 tarjetas) -->
        <!-- ========================================== -->
        <div class="row g-4 mb-4">
            <!-- Ventas Hoy -->
            <div class="col-md-4 col-lg-2">
                <a href="{{ route('ventas.index') }}" class="block-link">
                    <div class="card-box bg-red">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small>VENTAS HOY</small>
                                <h2 class="mb-0 mt-1">€{{ number_format($ventasHoy ?? 0, 0) }}</h2>
                                @isset($porcentajeVentas)
                                <small class="{{ $porcentajeVentas >= 0 ? 'trend-up' : 'trend-down' }}">
                                    <i class="bi bi-{{ $porcentajeVentas >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                    {{ abs($porcentajeVentas) }}%
                                </small>
                                @endisset
                            </div>
                            <div class="icon-box">💰</div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Ventas Totales -->
            <div class="col-md-4 col-lg-2">
                <a href="{{ route('ventas.index') }}" class="block-link">
                    <div class="card-box bg-pink">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small>VENTAS TOTALES</small>
                                <h2 class="mb-0 mt-1">€{{ number_format($ventasTotales ?? 0, 0) }}</h2>
                                <small>todo el historial</small>
                            </div>
                            <div class="icon-box">📈</div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Productos -->
            <div class="col-md-4 col-lg-2">
                <a href="{{ route('productos.index') }}" class="block-link">
                    <div class="card-box bg-darkblue">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small>PRODUCTOS</small>
                                <h2 class="mb-0 mt-1">{{ $totalProductos ?? 0 }}</h2>
                                <small>{{ $productosAgotados ?? 0 }} agotados</small>
                            </div>
                            <div class="icon-box">📦</div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Compras Pendientes -->
            <div class="col-md-4 col-lg-2">
                <a href="{{ route('compras.index') }}" class="block-link">
                    <div class="card-box bg-blue">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small>COMPRAS PEND.</small>
                                <h2 class="mb-0 mt-1">{{ $comprasPendientes ?? 0 }}</h2>
                                <small>por recibir</small>
                            </div>
                            <div class="icon-box">📦</div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Devoluciones Mes -->
            <div class="col-md-4 col-lg-2">
                <a href="{{ route('devoluciones.index') }}" class="block-link">
                    <div class="card-box bg-orange">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small>DEVOLUCIONES</small>
                                <h2 class="mb-0 mt-1">€{{ number_format($devolucionesMes ?? 0, 0) }}</h2>
                                <small>este mes</small>
                            </div>
                            <div class="icon-box">↩️</div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Stock Crítico -->
            <div class="col-md-4 col-lg-2">
                <a href="{{ route('productos.index') }}" class="block-link">
                    <div class="card-box {{ ($stockCritico ?? 0) > 0 ? 'bg-red' : 'bg-green' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small>STOCK CRÍTICO</small>
                                <h2 class="mb-0 mt-1">{{ $stockCritico ?? 0 }}</h2>
                                <small>productos</small>
                            </div>
                            <div class="icon-box">⚠️</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- FILA DE GRÁFICAS (2 columnas) -->
        <!-- ========================================== -->
        <div class="row">
            <!-- Gráfica 1: Ventas vs Devoluciones 7 días -->
            <div class="col-md-7">
                <div class="chart-container">
                    <div class="chart-title">
                        <i class="bi bi-graph-up"></i> Ventas vs Devoluciones (últimos 7 días)
                    </div>
                    <canvas id="ventasDevolucionesChart" height="250"></canvas>
                </div>
            </div>

            <!-- Gráfica 2: Top 5 Productos Más Vendidos -->
            <div class="col-md-5">
                <div class="chart-container">
                    <div class="chart-title">
                        <i class="bi bi-trophy"></i> Top 5 Productos Más Vendidos
                    </div>
                    <canvas id="topProductosChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <!-- Gráfica 3: Margen de ganancia por producto -->
            <div class="col-md-6">
                <div class="chart-container">
                    <div class="chart-title">
                        <i class="bi bi-pie-chart"></i> Margen de Ganancia Estimado
                    </div>
                    <canvas id="margenChart" height="250"></canvas>
                </div>
            </div>

            <!-- Gráfica 4: Movimientos de stock -->
            <div class="col-md-6">
                <div class="chart-container">
                    <div class="chart-title">
                        <i class="bi bi-arrow-left-right"></i> Movimientos de Stock (Entradas vs Salidas)
                    </div>
                    <canvas id="movimientosStockChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- ACTIVIDADES RECIENTES -->
        <!-- ========================================== -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="chart-title mb-3">
                    <i class="bi bi-clock-history"></i> Últimas Actividades
                </div>
                <div class="activity-list">
                    @forelse($ultimasActividades ?? [] as $actividad)
                    <div class="activity-item">
                        <div class="activity-icon bg-{{ $actividad->color }} bg-opacity-25">
                            <i class="bi {{ $actividad->icono }} text-{{ $actividad->color }}"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-text">{{ $actividad->descripcion }}</p>
                            <small class="activity-time">
                                <i class="bi bi-person-circle"></i> {{ $actividad->usuario }} • 
                                {{ $actividad->tiempo }}
                            </small>
                        </div>
                    </div>
                    @empty
                    <div class="activity-item">
                        <p class="text-muted text-center w-100 m-0">No hay actividades recientes</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ==========================================
        // GRÁFICA 1: Ventas vs Devoluciones 7 días
        // ==========================================
        @isset($ventas7Dias)
        const ventas7Dias = @json($ventas7Dias);
        const devoluciones7Dias = @json($devoluciones7Dias);
        
        // Crear array de fechas (últimos 7 días)
        const fechas = [];
        for (let i = 6; i >= 0; i--) {
            const fecha = new Date();
            fecha.setDate(fecha.getDate() - i);
            fechas.push(fecha.toLocaleDateString('es-ES', { day: '2-digit', month: 'short' }));
        }
        
        // Mapear datos
        const ventasData = fechas.map(fecha => {
            const venta = ventas7Dias.find(v => {
                const fechaVenta = new Date(v.fecha).toLocaleDateString('es-ES', { day: '2-digit', month: 'short' });
                return fechaVenta === fecha;
            });
            return venta ? venta.total : 0;
        });
        
        const devolucionesData = fechas.map(fecha => {
            const dev = devoluciones7Dias.find(d => {
                const fechaDev = new Date(d.fecha).toLocaleDateString('es-ES', { day: '2-digit', month: 'short' });
                return fechaDev === fecha;
            });
            return dev ? dev.total : 0;
        });
        
        new Chart(document.getElementById('ventasDevolucionesChart'), {
            type: 'line',
            data: {
                labels: fechas,
                datasets: [
                    {
                        label: 'Ventas (€)',
                        data: ventasData,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Devoluciones (€)',
                        data: devolucionesData,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { labels: { color: '#fff' } }
                },
                scales: {
                    y: { ticks: { color: '#fff' }, grid: { color: 'rgba(255,255,255,0.1)' } },
                    x: { ticks: { color: '#fff' }, grid: { color: 'rgba(255,255,255,0.1)' } }
                }
            }
        });
        @endisset

        // ==========================================
        // GRÁFICA 2: Top 5 Productos
        // ==========================================
        @isset($topProductos)
        const topProductos = @json($topProductos);
        new Chart(document.getElementById('topProductosChart'), {
            type: 'bar',
            data: {
                labels: topProductos.map(p => p.nombre.length > 15 ? p.nombre.substring(0, 15) + '...' : p.nombre),
                datasets: [{
                    label: 'Unidades Vendidas',
                    data: topProductos.map(p => p.total_vendido),
                    backgroundColor: '#e63946',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { labels: { color: '#fff' } }
                },
                scales: {
                    y: { ticks: { color: '#fff', stepSize: 1 }, grid: { color: 'rgba(255,255,255,0.1)' } },
                    x: { ticks: { color: '#fff' }, grid: { color: 'rgba(255,255,255,0.1)' } }
                }
            }
        });
        @endisset

        // ==========================================
        // GRÁFICA 3: Margen de ganancia
        // ==========================================
        @isset($margenes)
        const margenes = @json($margenes);
        new Chart(document.getElementById('margenChart'), {
            type: 'doughnut',
            data: {
                labels: margenes.map(m => m.nombre),
                datasets: [{
                    data: margenes.map(m => m.margen),
                    backgroundColor: ['#e63946', '#28a745', '#fd7e14', '#17a2b8', '#6f42c1']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { labels: { color: '#fff' } },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.label}: ${ctx.raw}% de margen`
                        }
                    }
                }
            }
        });
        @endisset

        // ==========================================
        // GRÁFICA 4: Movimientos de Stock
        // ==========================================
        @isset($movimientosStock)
        const movimientosStock = @json($movimientosStock);
        new Chart(document.getElementById('movimientosStockChart'), {
            type: 'line',
            data: {
                labels: movimientosStock.map(m => m.fecha),
                datasets: [
                    {
                        label: 'Entradas',
                        data: movimientosStock.map(m => m.entradas),
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Salidas',
                        data: movimientosStock.map(m => m.salidas),
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { labels: { color: '#fff' } }
                },
                scales: {
                    y: { ticks: { color: '#fff' }, grid: { color: 'rgba(255,255,255,0.1)' } },
                    x: { ticks: { color: '#fff' }, grid: { color: 'rgba(255,255,255,0.1)' } }
                }
            }
        });
        @endisset
    </script>

</body>
</html>

