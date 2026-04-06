<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $config->nombre_empresa }} - Órdenes de Compra</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
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
        <h1 class="mb-4">Órdenes de Compra</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- BOTÓN PARA ABRIR EL MODAL -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalCompra">
            + Nueva orden
        </button>

        <!-- BUSCADOR -->
        <div class="d-flex justify-content-end mb-3">
            <input type="text" id="buscador" class="form-control w-25" placeholder="Buscar por código o proveedor...">
        </div>

        <!-- TABLA -->
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Código</th>
                    <th>Proveedor</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-compras">
                @foreach($compras as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td>{{ $c->created_at->format('d/m/Y') }}</td>
                        <td class="codigo">{{ $c->numero_factura }}</td>
                        <td class="proveedor">{{ $c->proveedor->nombre ?? '—' }}</td>
                        <td>${{ number_format($c->total, 2) }}</td>
                        <td>
                            <span class="badge 
                                @if($c->estado === 'pendiente') bg-warning
                                @elseif($c->estado === 'recibido') bg-success
                                @else bg-secondary @endif">
                                {{ ucfirst($c->estado) }}
                            </span>
                        </td>
                        <td>{{ $c->usuario->name ?? '—' }}</td>
                        <td>
                            <select class="form-select accion-compra" data-id="{{ $c->id }}">
                                <option value="">Acciones</option>
                                <option value="ver">Ver</option>
                                <option value="eliminar">Eliminar</option>
                            </select>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p id="contador"><strong>Mostrando {{ count($compras) }} compras</strong></p>
    </div>

    <!-- MODAL CREAR COMPRA -->
    <div class="modal fade" id="modalCompra" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('compras.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Nueva Orden de Compra</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Proveedor</label>
                                <select name="proveedor_id" class="form-select" required>
                                    <option value="">Selecciona un proveedor...</option>
                                    @foreach($proveedores as $prov)
                                        <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Número de Factura</label>
                                <input type="text" name="numero_factura" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Fecha de Pedido</label>
                                <input type="date" name="fecha_pedido" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Fecha Entrega Esperada</label>
                                <input type="date" name="fecha_entrega_esperada" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Observaciones</label>
                            <textarea name="observaciones" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Subtotal</label>
                                <input type="number" name="subtotal" class="form-control" step="0.01" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Impuesto</label>
                                <input type="number" name="impuesto" class="form-control" step="0.01" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Total</label>
                            <input type="number" name="total" class="form-control" step="0.01" required>
                        </div
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL VER COMPRA -->
    <div class="modal fade" id="modalVerCompra" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles de la Orden de Compra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Código:</strong><br> <span id="ver_codigo"></span></p>
                            <p><strong>Proveedor:</strong><br> <span id="ver_proveedor"></span></p>
                            <p><strong>Fecha Pedido:</strong><br> <span id="ver_fecha_pedido"></span></p>
                        </div>

                        <div class="col-md-6">
                            <p><strong>Estado:</strong><br> <span id="ver_estado" class="badge"></span></p>
                            <p><strong>Total:</strong><br> <span id="ver_total" style="font-size: 1.2em; font-weight: bold;"></span></p>
                            <p><strong>Usuario:</strong><br> <span id="ver_usuario"></span></p>
                        </div>
                    </div>

                    <hr>

                    <h6>Productos Comprados</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered" id="tabla-detalles">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unit.</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="detalles-compra">
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <p><strong>Observaciones:</strong><br> <span id="ver_observaciones"></span></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS ACCIONES + BUSCADOR + CONTADOR + VER -->
    <script>
        // ACCIONES
        document.querySelectorAll('.accion-compra').forEach(select => {
            select.addEventListener('change', function () {
                const id = this.dataset.id;
                const accion = this.value;

                if (accion === 'ver') {
                    mostrarCompra(id);
                }

                if (accion === 'eliminar') {
    if (confirm('¿Seguro que deseas eliminar esta compra?')) {
        // Mostrar indicador de carga
        const select = this;
        select.disabled = true;
        
        fetch(`/compras/${id}/eliminar`, {
            method: 'DELETE',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'  // ← Importante: indicar que esperamos JSON
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Eliminar la fila de la tabla
                const fila = select.closest('tr');
                fila.remove();
                
                // Actualizar contador de filas
                const filasRestantes = document.querySelectorAll('#tabla-compras tr').length;
                const contador = document.getElementById('contador');
                const totalOriginal = document.querySelectorAll('#tabla-compras tr').length;
                contador.innerHTML = `<strong>Mostrando ${filasRestantes} de ${totalOriginal} compras</strong>`;
                
                // Mostrar mensaje de éxito
                mostrarAlerta('success', data.message);
            } else {
                mostrarAlerta('danger', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('danger', 'Error al eliminar la compra. Por favor, recarga la página.');
        })
        .finally(() => {
            // Reactivar el select
            select.disabled = false;
            select.value = ''; // Resetear el select
        });
    } else {
        // Resetear el select si cancela
        this.value = '';
    }
}

// Función auxiliar para mostrar alertas
function mostrarAlerta(tipo, mensaje) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const content = document.querySelector('.content');
    const titulo = content.querySelector('h1');
    content.insertBefore(alertDiv, titulo.nextSibling);
    
    // Auto-cerrar después de 3 segundos
    setTimeout(() => {
        if (alertDiv) alertDiv.remove();
    }, 3000);
}

                this.value = '';
            });
        });

        // FUNCIÓN PARA VER COMPRA
        function mostrarCompra(id) {
            fetch(`/compras/${id}/json`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('ver_codigo').textContent = data.numero_factura;
                    document.getElementById('ver_proveedor').textContent = data.proveedor?.nombre ?? '—';
                    document.getElementById('ver_fecha_pedido').textContent = new Date(data.fecha_pedido).toLocaleDateString('es-ES');
                    document.getElementById('ver_usuario').textContent = data.usuario?.name ?? '—';
                    document.getElementById('ver_total').textContent = '$' + parseFloat(data.total).toFixed(2);
                    document.getElementById('ver_observaciones').textContent = data.observaciones ?? '—';

                    const estado = document.getElementById('ver_estado');
                    estado.textContent = data.estado.charAt(0).toUpperCase() + data.estado.slice(1);
                    estado.className = 'badge ' + (
                        data.estado === 'pendiente' ? 'bg-warning' :
                        data.estado === 'recibido' ? 'bg-success' :
                        'bg-secondary'
                    );

                    // Llenar detalles de compra
                    const detalles = document.getElementById('detalles-compra');
                    detalles.innerHTML = '';

                    if (data.detalles && data.detalles.length > 0) {
                        data.detalles.forEach(det => {
                            const row = `
                                <tr>
                                    <td>${det.producto?.nombre ?? '—'}</td>
                                    <td>${det.cantidad}</td>
                                    <td>$${parseFloat(det.precio_unitario).toFixed(2)}</td>
                                    <td>$${parseFloat(det.subtotal).toFixed(2)}</td>
                                </tr>
                            `;
                            detalles.innerHTML += row;
                        });
                    } else {
                        detalles.innerHTML = '<tr><td colspan="4" class="text-center">Sin productos</td></tr>';
                    }

                    const modal = new bootstrap.Modal(document.getElementById('modalVerCompra'));
                    modal.show();
                });
        }

        // BUSCADOR + CONTADOR
        const buscador = document.getElementById('buscador');
        const filas = document.querySelectorAll('#tabla-compras tr');
        const contador = document.getElementById('contador');
        const total = filas.length;

        buscador.addEventListener('keyup', function () {
            const filtro = this.value.toLowerCase();
            let visibles = 0;

            filas.forEach(fila => {
                const codigo = fila.querySelector('.codigo')?.textContent.toLowerCase() || '';
                const proveedor = fila.querySelector('.proveedor')?.textContent.toLowerCase() || '';

                if (codigo.includes(filtro) || proveedor.includes(filtro)) {
                    fila.style.display = '';
                    visibles++;
                } else {
                    fila.style.display = 'none';
                }
            });

            contador.innerHTML = `<strong>Mostrando ${visibles} de ${total} compras</strong>`;
        });
    </script>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>