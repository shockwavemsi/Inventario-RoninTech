<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $config->nombre_empresa }} - Ventas</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ventas.css') }}">
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
        <h1 class="mb-4">Ventas</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('ventas.create') }}" class="btn btn-danger mb-3">
    Crear nueva venta
</a>

        <div class="d-flex justify-content-end mb-3">
            <input type="text" id="buscador" class="form-control w-25" placeholder="Buscar por cliente...">
        </div>

        <table class="table table-bordered bg-dark text-light">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Código</th>
                    <th>Cliente</th>
                    <th>Producto</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-ventas">
                @foreach($ventas as $v)
                    <tr>
                        <td>{{ $v->id }}</td>
                        <td>{{ $v->created_at->format('d/m/Y') }}</td>
                        <td class="codigo">{{ $v->numero_factura }}</td>
                        <td class="cliente">{{ $v->cliente }}</td>
                        <td>
                            @if($v->detalles->first())
                                {{ $v->detalles->first()->producto->nombre ?? '—' }}
                            @else
                                —
                            @endif
                        </td>
                        <td>
    @if($v->estado == 'completada')
        <span class="badge bg-success">Completada</span>
    @elseif($v->estado == 'pendiente')
        <span class="badge bg-warning text-dark">Pendiente</span>
    @else
        <span class="badge bg-danger">Cancelada</span>
    @endif
</td>
                        <td>
                            <select class="form-select accion-venta" data-id="{{ $v->id }}">
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
        <p id="contador"><strong>Mostrando {{ count($ventas) }} ventas</strong></p>
    </div>

    <!-- MODAL CREAR VENTA -->
    <div class="modal fade" id="modalVenta" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-light">
                <form action="{{ route('ventas.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Crear Venta</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Código Venta</label>
                            <input type="text" id="codigo_venta" class="form-control bg-dark text-light border-secondary" readonly>
                            <input type="hidden" name="numero_factura" id="numero_factura">
                        </div>

                        <div class="mb-3">
                            <label>Cliente</label>
                            <input type="text" name="cliente" class="form-control bg-dark text-light border-secondary" required>
                        </div>

                        <div class="mb-3">
                            <label>Producto</label>
                            <select name="producto_id" id="producto_select" class="form-select bg-dark text-light border-secondary" required>
                                <option value="">Selecciona un producto...</option>
                                @foreach($productos as $prod)
                                    <option value="{{ $prod->id }}" data-precio="{{ $prod->precio_venta }}">
                                        {{ $prod->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Cantidad</label>
                            <input type="number" name="cantidad" id="cantidad" class="form-control bg-dark text-light border-secondary" min="1" required>
                        </div>

                        <input type="hidden" name="productos_json" id="productos_json">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL VER VENTA -->
    <div class="modal fade" id="modalVerVenta" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles de Venta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Código:</strong><br> <span id="ver_codigo"></span></p>
                            <p><strong>Cliente:</strong><br> <span id="ver_cliente"></span></p>
                            <p><strong>Usuario:</strong><br> <span id="ver_usuario"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Fecha:</strong><br> <span id="ver_fecha"></span></p>
                            <p><strong>Total:</strong><br> $<span id="ver_total"></span></p>
                            <p><strong>Estado:</strong><br> <span id="ver_estado" class="badge"></span></p>
                        </div>
                    </div>
                    <hr>
                    <h6><strong>Productos Vendidos</strong></h6>
                    <table class="table table-sm table-dark">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="ver_detalles">
                            <!-- Se llena con JavaScript -->
                        </tbody>
                    </table>
                    <p><strong>Observaciones:</strong><br> <span id="ver_observaciones"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/ventas/ventas.js') }}"></script>

</body>
</html>