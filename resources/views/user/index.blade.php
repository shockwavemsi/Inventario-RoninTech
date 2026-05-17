<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SIS-INVENTARIOS - Panel User</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Tus estilos originales -->
    <link rel="stylesheet" href="{{ secure_asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/compras.css') }}">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    @php
        $user = auth()->user();
        $roleName = $user->role->name ?? 'user';
        $menuScript = $roleName === 'admin' ? 'js/menu.js' : 'js/userMenu.js';
    @endphp

    <style>
        /* =============================================
           ESTILOS PREMIUM SOLO PARA EL DASHBOARD
           ============================================= */
        :root {
            --bg-dark: #0f1119;
            --bg-card: #1a1d2e;
            --bg-card-hover: #22263a;
            --text-primary: #e9ecef;
            --text-secondary: #adb5bd;
            --text-muted: #6c757d;
            --border-light: rgba(255, 255, 255, 0.06);
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.2);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.3);
            --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.4);
            --radius-sm: 12px;
            --radius-md: 16px;
            --radius-lg: 20px;
            --transition: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .content {
            margin-left: 260px;
            padding: 28px 32px 40px;
            min-height: 100vh;
            background: var(--bg-dark);
            transition: margin-left var(--transition);
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .dashboard-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .header-date {
            background: var(--bg-card);
            padding: 10px 18px;
            border-radius: 30px;
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 500;
            border: 1px solid var(--border-light);
            white-space: nowrap;
        }

        .header-date i {
            margin-right: 6px;
            color: #e63946;
        }

        .alert-critical {
            background: linear-gradient(135deg, #2d1a1e, #3b1f24);
            border: 1px solid rgba(220, 53, 69, 0.3);
            border-radius: var(--radius-md);
            padding: 18px 24px;
            margin-bottom: 28px;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }

        .alert-critical::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #dc3545;
            border-radius: 4px 0 0 4px;
        }

        .alert-critical .btn-light {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            border-radius: 25px;
            padding: 8px 18px;
            font-weight: 500;
            font-size: 0.85rem;
            white-space: nowrap;
            transition: all var(--transition);
        }

        .alert-critical .btn-light:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        /* KPI Cards */
        .kpi-row {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .kpi-card {
            background: var(--bg-card);
            border-radius: var(--radius-md);
            padding: 20px 22px;
            border: 1px solid var(--border-light);
            box-shadow: var(--shadow-sm);
            transition: all var(--transition);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            cursor: default;
        }

        .kpi-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(255, 255, 255, 0.12);
            background: var(--bg-card-hover);
        }

        .kpi-icon {
            font-size: 2rem;
            margin-bottom: 12px;
            opacity: 0.8;
        }

        .kpi-label {
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .kpi-value {
            font-size: 1.7rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.1;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .kpi-sub {
            font-size: 0.78rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .kpi-trend-up { color: #2ecc71; font-weight: 600; }
        .kpi-trend-down { color: #e74c3c; font-weight: 600; }

        /* Gráficas */
        .chart-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            padding: 22px 24px;
            border: 1px solid var(--border-light);
            box-shadow: var(--shadow-md);
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: all var(--transition);
        }

        .chart-card:hover {
            box-shadow: var(--shadow-lg);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .chart-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border-light);
        }

        .chart-header i {
            font-size: 1.2rem;
            color: #e63946;
            background: rgba(230, 57, 70, 0.1);
            padding: 8px;
            border-radius: 8px;
        }

        .chart-header span {
            font-weight: 600;
            color: #fff;
            font-size: 0.95rem;
            letter-spacing: -0.2px;
        }

        .chart-card canvas {
            flex: 1;
            max-height: 300px;
            width: 100% !important;
        }

        .chart-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .chart-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        /* Actividades */
        .activity-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-light);
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .activity-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .activity-header i {
            font-size: 1.1rem;
            color: #e63946;
            background: rgba(230, 57, 70, 0.1);
            padding: 7px;
            border-radius: 8px;
        }

        .activity-header span {
            font-weight: 600;
            color: #fff;
            font-size: 0.95rem;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 14px 22px;
            border-bottom: 1px solid var(--border-light);
            transition: background var(--transition);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-item:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        /* Icono circular de actividad (como el original) */
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
            background: rgba(255,255,255,0.08); /* fallback */
        }

        .activity-content p {
            margin: 0;
            color: #e9ecef;
            font-size: 0.88rem;
            font-weight: 500;
        }

        .activity-content small {
            color: var(--text-muted);
            font-size: 0.75rem;
            display: block;
            margin-top: 3px;
        }

        .activity-empty {
            padding: 30px;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 1400px) {
            .kpi-row { grid-template-columns: repeat(3, 1fr); }
            .chart-grid { grid-template-columns: 1fr; }
            .chart-grid-2 { grid-template-columns: 1fr; }
        }

        @media (max-width: 992px) {
            .content { margin-left: 0; padding: 20px 16px; }
            .kpi-row { grid-template-columns: repeat(2, 1fr); }
            .dashboard-header { flex-direction: column; align-items: flex-start; }
        }

        @media (max-width: 576px) {
            .kpi-row { grid-template-columns: 1fr; }
            .kpi-value { font-size: 1.4rem; }
            .chart-card { padding: 16px; }
            .content { padding: 16px 12px; }
        }
    </style>

    <script src="{{ secure_asset($menuScript) }}" defer></script>
</head>

<body>
    <!-- BOTÓN HAMBURGUESA (ORIGINAL) -->
    <button id="menu-toggle" class="menu-toggle" aria-label="Abrir menú">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <!-- OVERLAY (ORIGINAL) -->
    <div id="sidebar-overlay" class="sidebar-overlay"></div>

    <!-- SIDEBAR (ORIGINAL) -->
    <div class="sidebar">
        <h3>{{ $config->nombre_empresa ?? 'SIS-INVENTARIOS' }}</h3>
        <div id="menu-contenedor"></div>
        <a href="{{ route('logout') }}" class="mt-4">
            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
        </a>
    </div>

    <!-- ==========================================
         CONTENIDO DEL DASHBOARD (DISEÑO PREMIUM)
         ========================================== -->
    <div class="content">

        <!-- ENCABEZADO -->
        <div class="dashboard-header">
            <h1>📊 Panel de Control</h1>
            <div class="header-date">
                <i class="bi bi-calendar3"></i>
                <span id="fecha-actual"></span>
            </div>
        </div>

        <!-- ALERTA DE STOCK CRÍTICO -->
        @php
            use App\Models\Producto;
            $productosCriticos = Producto::whereRaw('stock_actual <= stock_minimo')
                ->where('activo', true)
                ->limit(5)
                ->get();
        @endphp

        @if($productosCriticos->count() > 0)
        <div class="alert-critical alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <i class="bi bi-exclamation-triangle-fill fs-3" style="color: #dc3545;"></i>
                <div class="flex-grow-1">
                    <strong style="color: #fff; font-size: 1rem;">⚠️ Stock Crítico Detectado</strong>
                    <div class="row mt-2 g-2">
                        @foreach($productosCriticos as $producto)
                        <div class="col-md-4 col-sm-6">
                            <small style="color: #f1aeb5;">
                                <strong>{{ $producto->nombre }}</strong> →
                                {{ $producto->stock_actual }} / {{ $producto->stock_minimo }}
                            </small>
                        </div>
                        @endforeach
                    </div>
                </div>
                <a href="{{ route('productos.index') }}" class="btn btn-sm btn-light">
                    <i class="bi bi-eye"></i> Ver Todos
                </a>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        </div>
        @endif

        <!-- KPI CARDS (con los emojis originales) -->
        <div class="kpi-row">
            <!-- Ventas Hoy -->
            <div class="kpi-card">
                <div class="kpi-icon">💰</div>
                <div>
                    <div class="kpi-label">VENTAS HOY</div>
                    <div class="kpi-value">€{{ number_format($ventasHoy ?? 0, 0) }}</div>
                    @isset($porcentajeVentas)
                    <div class="kpi-sub">
                        <span class="{{ $porcentajeVentas >= 0 ? 'kpi-trend-up' : 'kpi-trend-down' }}">
                            <i class="bi bi-{{ $porcentajeVentas >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ abs($porcentajeVentas) }}%
                        </span>
                        <span>vs ayer</span>
                    </div>
                    @endisset
                </div>
            </div>

            <!-- Ventas Totales -->
            <div class="kpi-card">
                <div class="kpi-icon">📈</div>
                <div>
                    <div class="kpi-label">VENTAS TOTALES</div>
                    <div class="kpi-value">€{{ number_format($ventasTotales ?? 0, 0) }}</div>
                    <div class="kpi-sub">todo el historial</div>
                </div>
            </div>

            <!-- Productos -->
            <div class="kpi-card">
                <div class="kpi-icon">📦</div>
                <div>
                    <div class="kpi-label">PRODUCTOS</div>
                    <div class="kpi-value">{{ $totalProductos ?? 0 }}</div>
                    <div class="kpi-sub">{{ $productosAgotados ?? 0 }} agotados</div>
                </div>
            </div>

            <!-- Compras Pendientes -->
            <div class="kpi-card">
                <div class="kpi-icon">📦</div>
                <div>
                    <div class="kpi-label">COMPRAS PEND.</div>
                    <div class="kpi-value">{{ $comprasPendientes ?? 0 }}</div>
                    <div class="kpi-sub">por recibir</div>
                </div>
            </div>

            <!-- Devoluciones -->
            <div class="kpi-card">
                <div class="kpi-icon">↩️</div>
                <div>
                    <div class="kpi-label">DEVOLUCIONES</div>
                    <div class="kpi-value">€{{ number_format($devolucionesMes ?? 0, 0) }}</div>
                    <div class="kpi-sub">este mes</div>
                </div>
            </div>

            <!-- Stock Crítico -->
            <div class="kpi-card">
                <div class="kpi-icon">⚠️</div>
                <div>
                    <div class="kpi-label">STOCK CRÍTICO</div>
                    <div class="kpi-value">{{ $stockCritico ?? 0 }}</div>
                    <div class="kpi-sub">productos</div>
                </div>
            </div>
        </div>

        <!-- GRÁFICAS FILA 1 -->
        <div class="chart-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <i class="bi bi-graph-up"></i>
                    <span>Ventas vs Devoluciones (7 días)</span>
                </div>
                <canvas id="ventasDevolucionesChart"></canvas>
            </div>
            <div class="chart-card">
                <div class="chart-header">
                    <i class="bi bi-trophy"></i>
                    <span>Top 5 Productos Más Vendidos</span>
                </div>
                <canvas id="topProductosChart"></canvas>
            </div>
        </div>

        <!-- GRÁFICAS FILA 2 -->
        <div class="chart-grid-2">
            <div class="chart-card">
                <div class="chart-header">
                    <i class="bi bi-pie-chart"></i>
                    <span>Margen de Ganancia Estimado</span>
                </div>
                <canvas id="margenChart"></canvas>
            </div>
            <div class="chart-card">
                <div class="chart-header">
                    <i class="bi bi-arrow-left-right"></i>
                    <span>Movimientos de Stock (Entradas vs Salidas)</span>
                </div>
                <canvas id="movimientosStockChart"></canvas>
            </div>
        </div>

        <!-- ACTIVIDADES RECIENTES (con los iconos originales) -->
        <div class="activity-card">
            <div class="activity-header">
                <i class="bi bi-clock-history"></i>
                <span>Últimas Actividades</span>
            </div>
            @forelse($ultimasActividades ?? [] as $actividad)
            <div class="activity-item">
                <div class="activity-icon" style="background: {{ $actividad->color ?? '#6c757d' }}20; color: {{ $actividad->color ?? '#6c757d' }};">
                    <i class="bi {{ $actividad->icono ?? 'bi-circle' }}"></i>
                </div>
                <div class="activity-content">
                    <p>{{ $actividad->descripcion }}</p>
                    <small>
                        <i class="bi bi-person-circle"></i> {{ $actividad->usuario ?? 'Sistema' }} &nbsp;•&nbsp;
                        {{ $actividad->tiempo ?? 'Hace un momento' }}
                    </small>
                </div>
            </div>
            @empty
            <div class="activity-empty">
                <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 8px;"></i>
                No hay actividades recientes
            </div>
            @endforelse
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Fecha en tiempo real
        document.getElementById('fecha-actual').textContent = new Date().toLocaleDateString('es-ES', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });

        // Configuración global de Chart.js
        Chart.defaults.color = '#adb5bd';
        Chart.defaults.borderColor = 'rgba(255,255,255,0.06)';
        Chart.defaults.font.family = "'Inter', 'Segoe UI', system-ui, sans-serif";
        Chart.defaults.font.size = 11;
        Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(26,29,46,0.95)';
        Chart.defaults.plugins.tooltip.titleColor = '#fff';
        Chart.defaults.plugins.tooltip.bodyColor = '#e9ecef';
        Chart.defaults.plugins.tooltip.borderColor = 'rgba(255,255,255,0.1)';
        Chart.defaults.plugins.tooltip.borderWidth = 1;
        Chart.defaults.plugins.tooltip.padding = 12;
        Chart.defaults.plugins.tooltip.cornerRadius = 8;
        Chart.defaults.animation.duration = 800;

        // ========== GRÁFICA 1: Ventas vs Devoluciones ==========
        @isset($ventas7Dias)
        const ventas7Dias = @json($ventas7Dias);
        const devoluciones7Dias = @json($devoluciones7Dias);
        const fechas = [];
        for (let i = 6; i >= 0; i--) {
            const fecha = new Date();
            fecha.setDate(fecha.getDate() - i);
            fechas.push(fecha.toLocaleDateString('es-ES', { day: '2-digit', month: 'short' }));
        }
        const ventasData = fechas.map(fecha => {
            const venta = ventas7Dias.find(v => {
                const fv = new Date(v.fecha).toLocaleDateString('es-ES', { day: '2-digit', month: 'short' });
                return fv === fecha;
            });
            return venta ? venta.total : 0;
        });
        const devolucionesData = fechas.map(fecha => {
            const dev = devoluciones7Dias.find(d => {
                const fd = new Date(d.fecha).toLocaleDateString('es-ES', { day: '2-digit', month: 'short' });
                return fd === fecha;
            });
            return dev ? dev.total : 0;
        });

        new Chart(document.getElementById('ventasDevolucionesChart'), {
            type: 'line',
            data: {
                labels: fechas,
                datasets: [{
                    label: 'Ventas (€)',
                    data: ventasData,
                    borderColor: '#2ecc71',
                    backgroundColor: 'rgba(46,204,113,0.08)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#2ecc71',
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    borderWidth: 2.5,
                }, {
                    label: 'Devoluciones (€)',
                    data: devolucionesData,
                    borderColor: '#e74c3c',
                    backgroundColor: 'rgba(231,76,60,0.06)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#e74c3c',
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    borderWidth: 2.5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { labels: { usePointStyle: true, pointStyleWidth: 8, padding: 20, color: '#adb5bd' } }
                },
                scales: {
                    y: { ticks: { color: '#6c757d', callback: v => '€' + v }, grid: { color: 'rgba(255,255,255,0.04)' }, beginAtZero: true },
                    x: { ticks: { color: '#6c757d' }, grid: { display: false } }
                }
            }
        });
        @endisset

        // ========== GRÁFICA 2: Top 5 Productos ==========
        @isset($topProductos)
        const topProductos = @json($topProductos);
        new Chart(document.getElementById('topProductosChart'), {
            type: 'bar',
            data: {
                labels: topProductos.map(p => p.nombre.length > 15 ? p.nombre.substring(0, 15) + '...' : p.nombre),
                datasets: [{
                    label: 'Unidades Vendidas',
                    data: topProductos.map(p => p.total_vendido),
                    backgroundColor: ['rgba(230,57,70,0.85)','rgba(230,57,70,0.7)','rgba(230,57,70,0.55)','rgba(230,57,70,0.4)','rgba(230,57,70,0.25)'],
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: '#6c757d', stepSize: 1 }, grid: { color: 'rgba(255,255,255,0.04)' } },
                    y: { ticks: { color: '#adb5bd' }, grid: { display: false } }
                }
            }
        });
        @endisset

        // ========== GRÁFICA 3: Margen de Ganancia ==========
        @isset($margenes)
        const margenes = @json($margenes);
        new Chart(document.getElementById('margenChart'), {
            type: 'doughnut',
            data: {
                labels: margenes.map(m => m.nombre),
                datasets: [{
                    data: margenes.map(m => m.margen),
                    backgroundColor: ['#e63946','#2ecc71','#fd7e14','#17a2b8','#6f42c1','#f39c12'],
                    borderColor: 'rgba(15,17,25,0.8)',
                    borderWidth: 3,
                    hoverBorderWidth: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, pointStyleWidth: 8, padding: 16, color: '#adb5bd' } },
                    tooltip: { callbacks: { label: (ctx) => ` ${ctx.label}: ${ctx.raw}% de margen` } }
                }
            }
        });
        @endisset

        // ========== GRÁFICA 4: Movimientos de Stock ==========
        @isset($movimientosStock)
        const movimientosStock = @json($movimientosStock);
        new Chart(document.getElementById('movimientosStockChart'), {
            type: 'line',
            data: {
                labels: movimientosStock.map(m => m.fecha),
                datasets: [{
                    label: 'Entradas',
                    data: movimientosStock.map(m => m.entradas),
                    borderColor: '#2ecc71',
                    backgroundColor: 'rgba(46,204,113,0.08)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    borderWidth: 2.5,
                }, {
                    label: 'Salidas',
                    data: movimientosStock.map(m => m.salidas),
                    borderColor: '#e74c3c',
                    backgroundColor: 'rgba(231,76,60,0.06)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    borderWidth: 2.5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { labels: { usePointStyle: true, pointStyleWidth: 8, padding: 20, color: '#adb5bd' } }
                },
                scales: {
                    y: { ticks: { color: '#6c757d' }, grid: { color: 'rgba(255,255,255,0.04)' }, beginAtZero: true },
                    x: { ticks: { color: '#6c757d' }, grid: { display: false } }
                }
            }
        });
        @endisset
    </script>
</body>
</html>