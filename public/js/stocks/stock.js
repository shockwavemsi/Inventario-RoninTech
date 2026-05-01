// public/js/stocks/stock.js

let productosOriginal = [];
let productosFiltrados = [];
let filtroActual = 'todos';

// Función para determinar el estado
function determinarEstado(stock, minimo, maximo) {
    if (stock <= 0) return 'agotado';
    if (stock <= minimo) return 'bajo';
    if (stock >= maximo) return 'exceso';
    return 'normal';
}

// Obtener clase y icono según estado
function getEstadoInfo(stock, minimo, maximo) {
    const estado = determinarEstado(stock, minimo, maximo);

    const info = {
agotado: { clase: 'badge-danger', icono: 'bi-x-circle-fill', label: 'Agotado' },
bajo: { clase: 'badge-warning', icono: 'bi-exclamation-triangle-fill', label: 'Bajo' },
exceso: { clase: 'badge-info', icono: 'bi-arrow-up-circle-fill', label: 'Exceso' },
normal: { clase: 'badge-success', icono: 'bi-check-circle-fill', label: 'Normal' }
    };

    return { estado, ...info[estado] };
}

// Generar tarjetas de estadísticas con puro DOM
function generarEstadisticas() {
    const container = document.getElementById('estadisticas-container');
    container.innerHTML = '';

    const stats = {
        total: productosOriginal.length,
        bajo: productosOriginal.filter(p => p.stock_actual > 0 && p.stock_actual <= p.stock_minimo).length,
        agotado: productosOriginal.filter(p => p.stock_actual <= 0).length,
        normal: productosOriginal.filter(p => p.stock_actual > p.stock_minimo && p.stock_actual < p.stock_maximo).length
    };

    const estadisticas = [
        { label: 'Total Productos', valor: stats.total, icono: 'bi-box', delay: 0.1 },
        { label: 'Stock Bajo', valor: stats.bajo, icono: 'bi-exclamation-triangle-fill', delay: 0.2 },
        { label: 'Agotados', valor: stats.agotado, icono: 'bi-x-circle-fill', delay: 0.3 },
        { label: 'Normal', valor: stats.normal, icono: 'bi-check-circle-fill', delay: 0.4 }
    ];

    estadisticas.forEach(stat => {
        const col = document.createElement('div');
        col.className = 'col-md-3 col-sm-6 mb-3';

        const card = document.createElement('div');
        card.className = 'stat-card';
        card.style.animationDelay = `${stat.delay}s`;

        const flex = document.createElement('div');
        flex.className = 'd-flex align-items-center gap-3';

        const icono = document.createElement('i');
        icono.className = `bi ${stat.icono}`;
        icono.style.fontSize = '2.5rem';
        icono.style.color = 'var(--neon-red)';
        icono.style.opacity = '0.7';

        const textDiv = document.createElement('div');

        const label = document.createElement('div');
        label.className = 'stat-label';
        label.textContent = stat.label;

        const valor = document.createElement('div');
        valor.className = 'stat-value';
        valor.textContent = stat.valor;

        textDiv.appendChild(label);
        textDiv.appendChild(valor);
        flex.appendChild(icono);
        flex.appendChild(textDiv);
        card.appendChild(flex);
        col.appendChild(card);
        container.appendChild(col);
    });
}

// Renderizar tabla con puro DOM
function renderizarTabla(productos) {
    const tbody = document.getElementById('tabla-stock');
    tbody.innerHTML = '';

    if (productos.length === 0) {
        const trVacio = document.createElement('tr');
        trVacio.innerHTML = '<td colspan="7" class="text-center text-muted py-4"><i class="bi bi-inbox"></i> Sin resultados</td>';
        tbody.appendChild(trVacio);

        const contador = document.getElementById('contador');
        contador.innerHTML = '';
        const iContador = document.createElement('i');
        iContador.className = 'bi bi-info-circle';
        const strongContador = document.createElement('strong');
        strongContador.textContent = 'Mostrando 0 productos';
        contador.appendChild(iContador);
        contador.appendChild(document.createTextNode(' '));
        contador.appendChild(strongContador);
        return;
    }

    productos.forEach((producto, idx) => {
        const estadoInfo = getEstadoInfo(producto.stock_actual, producto.stock_minimo, producto.stock_maximo);

        const tr = document.createElement('tr');
        tr.style.animation = `slideInUp 0.4s ease-out forwards`;
        tr.style.animationDelay = `${idx * 0.05}s`;

        const tdFecha = document.createElement('td');
        tdFecha.textContent = producto.fecha_creacion;
        tr.appendChild(tdFecha);

        const tdNombre = document.createElement('td');
        tdNombre.className = 'nombre';
        const iNombre = document.createElement('i');
        iNombre.className = 'bi bi-box2';
        const spanNombre = document.createElement('span');
        spanNombre.textContent = ` ${producto.nombre}`;
        tdNombre.appendChild(iNombre);
        tdNombre.appendChild(spanNombre);
        tr.appendChild(tdNombre);

        const tdCategoria = document.createElement('td');
        tdCategoria.textContent = producto.categoria;
        tr.appendChild(tdCategoria);

        const tdProveedor = document.createElement('td');
        tdProveedor.className = 'proveedor';
        tdProveedor.textContent = producto.proveedor;
        tr.appendChild(tdProveedor);

        const tdStock = document.createElement('td');
        tdStock.className = 'text-center';
        const strongStock = document.createElement('strong');
        strongStock.textContent = producto.stock_actual;
        tdStock.appendChild(strongStock);
        tr.appendChild(tdStock);

        const tdMinMax = document.createElement('td');
        tdMinMax.className = 'text-center';
        tdMinMax.textContent = `${producto.stock_minimo} / ${producto.stock_maximo}`;
        tr.appendChild(tdMinMax);

        const tdEstado = document.createElement('td');

        const badge = document.createElement('span');
        badge.className = `badge estado-badge px-3 py-2 ${estadoInfo.clase}`;

        const iEstado = document.createElement('i');
        iEstado.className = `bi ${estadoInfo.icono} me-1`;

        const spanEstado = document.createElement('span');
        spanEstado.textContent = estadoInfo.label;

        badge.appendChild(iEstado);
        badge.appendChild(spanEstado);
        tdEstado.appendChild(badge);
        tr.appendChild(tdEstado);

        tbody.appendChild(tr);
    });

    const contador = document.getElementById('contador');
    contador.innerHTML = '';

    const iContador = document.createElement('i');
    iContador.className = 'bi bi-info-circle';

    const strongContador = document.createElement('strong');
    strongContador.textContent = `Mostrando ${productos.length} productos`;

    contador.appendChild(iContador);
    contador.appendChild(document.createTextNode(' '));
    contador.appendChild(strongContador);
}

// Aplicar filtros
function aplicarFiltros() {
    const buscador = document.getElementById('buscador').value.toLowerCase();

    productosFiltrados = productosOriginal.filter(producto => {
        const pasaFiltroEstado = filtroActual === 'todos' || determinarEstado(producto.stock_actual, producto.stock_minimo, producto.stock_maximo) === filtroActual;
        const pasaBuscador = producto.nombre.toLowerCase().includes(buscador) || producto.proveedor.toLowerCase().includes(buscador);

        return pasaFiltroEstado && pasaBuscador;
    });

    renderizarTabla(productosFiltrados);
}

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    productosOriginal = window.productosData || [];

    // Siempre usar 'todos' como filtro por defecto
    filtroActual = 'todos';

    generarEstadisticas();
    renderizarTabla(productosOriginal);

    // Establecer select a 'todos'
    const selectFiltro = document.getElementById('filtro-stock');
    if (selectFiltro) {
        selectFiltro.value = 'todos';

        selectFiltro.addEventListener('change', function() {
            filtroActual = this.value;
            aplicarFiltros();
        });
    }

    const buscador = document.getElementById('buscador');
    if (buscador) {
        buscador.addEventListener('input', aplicarFiltros);
    }

    console.log('✅ Stock.js cargado - Filtro por defecto: Todos');
});