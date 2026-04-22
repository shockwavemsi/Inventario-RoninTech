<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $config->nombre_empresa }} - Productos</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/stock.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/menu.js') }}"></script>
</head>
<body>
    <div class="sidebar">
        <h3>{{ $config->nombre_empresa }}</h3>
        <div id="menu-contenedor"></div>
        <a href="{{ route('logout') }}" class="mt-4">Cerrar sesión</a>
    </div>
    
    <div class="content">
        <h1 class="mb-4">Productos</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <button class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#modalProducto">
            Crear nuevo producto
        </button>

        <div class="d-flex justify-content-end mb-3">
            <input type="text" id="buscador" class="form-control w-25" placeholder="Buscar por nombre...">
        </div>

        <table class="table table-bordered bg-white">
    <thead>
        <tr>
            
            <th>Fecha de Creación</th>
            <th>Nombre</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Categoría</th>
            <th>Proveedor</th>
            <th>Precio Compra</th>
            <th>Precio Venta</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody id="tabla-productos">
        @foreach($productos as $prod)
            <tr>
                <td>{{ $prod->created_at->format('d/m/Y') }}</td>
                <td class="nombre">{{ $prod->nombre }}</td>
                <td>{{ $prod->marca ?? '—' }}</td>
                <td>{{ $prod->modelo ?? '—' }}</td>
                <td>{{ $prod->categoria->nombre ?? '—' }}</td>
                <td>{{ $prod->proveedor->nombre ?? '—' }}</td>
                <td>${{ $prod->precio_compra ?? '—' }}</td>
                <td>${{ $prod->precio_venta ?? '—' }}</td>
                <td>{{ $prod->activo ? 'Activo' : 'Inactivo' }}</td>
                <td>
                    <select class="form-select accion-producto" data-id="{{ $prod->id }}">
                        <option value="">Acciones</option>
                        <option value="ver">Ver</option>
                        <option value="editar">Editar</option>
                        <option value="eliminar">Eliminar</option>
                    </select>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
        <p id="contador"><strong>Mostrando {{ count($productos) }} productos</strong></p>
    </div>

    <!-- MODAL AGREGAR PRODUCTO -->
    <div class="modal fade" id="modalProducto" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('productos.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Nombre</label>
                                    <input type="text" name="nombre" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Marca</label>
                                    <input type="text" name="marca" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Modelo</label>
                                    <input type="text" name="modelo" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Categoría</label>
                                    <select name="categoria_id" class="form-select" required>
                                        <option value="">Selecciona una categoría...</option>
                                        @foreach($categorias as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Proveedor</label>
                                    <select name="proveedor_id" class="form-select" required>
                                        <option value="">Selecciona un proveedor...</option>
                                        @foreach($proveedores as $prov)
                                            <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Ubicación</label>
                                    <input type="text" name="ubicacion" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Precio Compra</label>
                                    <input type="number" name="precio_compra" class="form-control" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Precio Venta</label>
                                    <input type="number" name="precio_venta" class="form-control" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Stock Actual</label>
                                    <input type="number" name="stock_actual" class="form-control" value="0" step="1">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Stock Mínimo</label>
                                    <input type="number" name="stock_minimo" class="form-control" value="3">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Stock Máximo</label>
                                    <input type="number" name="stock_maximo" class="form-control" value="100">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Estado</label>
                            <select name="activo" class="form-select">
                                <option value="1">Activo</option>
                                <option value="0">Desactivado</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL VER PRODUCTO -->
    <div class="modal fade" id="modalVerProducto" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Información de Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Nombre:</strong><br> <span id="ver_nombre"></span></p>
                    <p><strong>Marca:</strong><br> <span id="ver_marca"></span></p>
                    <p><strong>Modelo:</strong><br> <span id="ver_modelo"></span></p>
                    <p><strong>Categoría:</strong><br> <span id="ver_categoria"></span></p>
                    <p><strong>Proveedor:</strong><br> <span id="ver_proveedor"></span></p>
                    <p><strong>Descripción:</strong><br> <span id="ver_descripcion"></span></p>
                    <p><strong>Precio Compra:</strong><br> $<span id="ver_precio_compra"></span></p>
                    <p><strong>Precio Venta:</strong><br> $<span id="ver_precio_venta"></span></p>
                    <p><strong>Stock Actual:</strong><br> <span id="ver_stock_actual"></span></p>
                    <p><strong>Stock Mínimo:</strong><br> <span id="ver_stock_minimo"></span></p>
                    <p><strong>Stock Máximo:</strong><br> <span id="ver_stock_maximo"></span></p>
                    <p><strong>Ubicación:</strong><br> <span id="ver_ubicacion"></span></p>
                    <p><strong>Estado:</strong><br>
                        <span id="ver_estado" class="badge"></span>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // ACCIONES
        document.querySelectorAll('.accion-producto').forEach(select => {
            select.addEventListener('change', function () {
                const id = this.dataset.id;
                const accion = this.value;
                if (accion === 'ver') {
                    mostrarProducto(id);
                }
                if (accion === 'editar') {
                    window.location.href = `/productos/${id}/editar`;
                }
                if (accion === 'eliminar') {
                    if (confirm('¿Seguro que deseas eliminar este producto?')) {
                        fetch(`/productos/${id}/eliminar`, {
                            method: 'DELETE',
                            headers: { 
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(res => {
                            if (res.ok) {
                                window.location.reload();
                            } else {
                                alert('Error al eliminar');
                            }
                        })
                        .catch(err => {
                            console.error('Error:', err);
                            alert('Error en la solicitud');
                        });
                    }
                }
                this.value = '';
            });
        });

        // FUNCIÓN PARA VER PRODUCTO (ARREGLADA CON SOLUCIÓN 1)
        function mostrarProducto(id) {
            fetch(`/productos/${id}/json`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('ver_nombre').textContent = data.nombre;
                    document.getElementById('ver_marca').textContent = data.marca ?? '—';
                    document.getElementById('ver_modelo').textContent = data.modelo ?? '—';
                    document.getElementById('ver_categoria').textContent = data.categoria?.nombre ?? '—';
                    document.getElementById('ver_proveedor').textContent = data.proveedor?.nombre ?? '—';
                    document.getElementById('ver_descripcion').textContent = data.descripcion ?? '—';
                    document.getElementById('ver_precio_compra').textContent = data.precio_compra ?? '—';
                    document.getElementById('ver_precio_venta').textContent = data.precio_venta ?? '—';
                    document.getElementById('ver_stock_actual').textContent = data.stock_actual ?? '0';
                    document.getElementById('ver_stock_minimo').textContent = data.stock_minimo ?? '3';
                    document.getElementById('ver_stock_maximo').textContent = data.stock_maximo ?? '100';
                    document.getElementById('ver_ubicacion').textContent = data.ubicacion ?? '—';
                    
                    const estado = document.getElementById('ver_estado');
                    estado.textContent = data.activo ? 'Activo' : 'Inactivo';
                    estado.className = data.activo ? 'badge bg-success' : 'badge bg-danger';
                    
                    const modalElement = document.getElementById('modalVerProducto');
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                    
                    // SOLUCIÓN: Forzar limpieza cuando se cierre el modal
                    modalElement.addEventListener('hidden.bs.modal', function () {
                        // Eliminar manualmente el backdrop si existe
                        const backdrops = document.querySelectorAll('.modal-backdrop');
                        backdrops.forEach(backdrop => backdrop.remove());
                        
                        // Restaurar el scroll del body
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    }, { once: true }); // El { once: true } evita que se ejecute múltiples veces
                })
                .catch(error => {
                    console.error('Error al cargar el producto:', error);
                    alert('Error al cargar los datos del producto');
                });
        }

        // BUSCADOR + CONTADOR
        const buscador = document.getElementById('buscador');
        const filas = document.querySelectorAll('#tabla-productos tr');
        const contador = document.getElementById('contador');
        const total = filas.length;

        if (buscador) {
            buscador.addEventListener('keyup', function () {
                const filtro = this.value.toLowerCase();
                let visibles = 0;
                filas.forEach(fila => {
                    const nombre = fila.querySelector('.nombre')?.textContent.toLowerCase() || '';
                    if (nombre.includes(filtro)) {
                        fila.style.display = '';
                        visibles++;
                    } else {
                        fila.style.display = 'none';
                    }
                });
                contador.innerHTML = `<strong>Mostrando ${visibles} de ${total} productos</strong>`;
            });
        }
        
        // También arreglamos el modal de crear producto por si acaso
        const modalCrearElement = document.getElementById('modalProducto');
        if (modalCrearElement) {
            modalCrearElement.addEventListener('hidden.bs.modal', function () {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => backdrop.remove());
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            });
        }
    </script>
</body>
</html>