<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $config->nombre_empresa }} - Órdenes de Compra</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        <a href="{{ route('compras.create') }}" class="btn btn-primary mb-3">
    + Nueva orden
</a>

        <!-- BUSCADOR -->
        <div class="d-flex justify-content-end mb-3">
            <input type="text" id="buscador" class="form-control w-25" placeholder="Buscar por código o proveedor...">
        </div>

        <!-- TABLA -->
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
            <th style="width: 150px">Acciones</th>
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
                    <span class="badge estado-badge px-3 py-2
                        @if($c->estado === 'pendiente') bg-warning text-dark
                        @elseif($c->estado === 'recibido') bg-success
                        @else bg-secondary @endif">
                        <i class="bi 
                            @if($c->estado === 'pendiente') bi-clock-history
                            @elseif($c->estado === 'recibido') bi-check-circle-fill
                            @else bi-question-circle @endif
                            me-1"></i>
                        {{ ucfirst($c->estado) }}
                    </span>
                </td>
                <td>{{ $c->usuario->name ?? '—' }}</td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-info ver-compra" data-id="{{ $c->id }}" title="Ver detalles">
                            <i class="bi bi-eye"></i>
                        </button>
                        
                        @if($c->estado === 'pendiente')
                            <button type="button" class="btn btn-success cambiar-estado" data-id="{{ $c->id }}" data-estado="recibido" title="Recibir compra">
                                <i class="bi bi-check-circle"></i>
                            </button>
                        @endif
                        
                        <button type="button" class="btn btn-danger eliminar-compra" data-id="{{ $c->id }}" title="Eliminar compra">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

        <p id="contador"><strong>Mostrando {{ count($compras) }} compras</strong></p>
    </div>

    <!-- MODAL CREAR COMPRA -->
    

    <!-- MODAL VER COMPRA -->
    <div class="modal fade" id="modalVerCompra" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-receipt"></i> Detalles de la Orden de Compra
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">Información General</h6>
                                    <p class="mb-1"><strong>Código:</strong> <span id="ver_codigo" class="badge bg-dark"></span></p>
                                    <p class="mb-1"><strong>Proveedor:</strong> <span id="ver_proveedor"></span></p>
                                    <p class="mb-1"><strong>Fecha Pedido:</strong> <span id="ver_fecha_pedido"></span></p>
                                    <p class="mb-1"><strong>Fecha Entrega Esperada:</strong> <span id="ver_fecha_entrega"></span></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">Estado y Totales</h6>
                                    <p class="mb-1"><strong>Estado:</strong> <span id="ver_estado" class="badge"></span></p>
                                    <p class="mb-1"><strong>Usuario:</strong> <span id="ver_usuario"></span></p>
                                    <p class="mb-1"><strong>Fecha Creación:</strong> <span id="ver_created_at"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6 class="mb-3"><i class="bi bi-box-seam"></i> Productos</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Producto</th>
                                    <th>Marca/Modelo</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Precio Unit.</th>
                                    <th class="text-end">Descuento</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="detalles-compra">
                                <tr><td colspan="7" class="text-center text-muted">Seleccione una compra para ver los detalles</td></tr>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="6" class="text-end fw-bold">SUBTOTAL:</td>
                                    <td class="text-end fw-bold" id="ver_subtotal">$0.00</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-end fw-bold">DESCUENTO:</td>
                                    <td class="text-end fw-bold text-danger" id="ver_descuento">$0.00</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-end fw-bold">IMPUESTO (21%):</td>
                                    <td class="text-end fw-bold" id="ver_impuesto">$0.00</td>
                                </tr>
                                <tr class="table-active">
                                    <td colspan="6" class="text-end fw-bold fs-5">TOTAL:</td>
                                    <td class="text-end fw-bold fs-5 text-primary" id="ver_total">$0.00</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="alert alert-secondary mt-3">
                        <strong><i class="bi bi-chat-left-text"></i> Observaciones:</strong><br>
                        <span id="ver_observaciones"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cerrar
                    </button>
                    <button type="button" class="btn btn-primary" onclick="window.print()">
                        <i class="bi bi-printer"></i> Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- TUS SCRIPTS SEPARADOS -->
<script src="{{ asset('js/compras/compras-estado.js') }}"></script>
<script src="{{ asset('js/compras/compras.js') }}"></script>
<script src="{{ asset('js/compras/compras-ver.js') }}"></script>
<script src="{{ asset('js/compras/compras-eliminar.js') }}"></script>
<script src="{{ asset('js/compras/compras-crear.js') }}"></script>
<script src="{{ asset('js/compras/compras-buscador.js') }}"></script>
<script src="{{ asset('js/compras/model-fix.js') }}"></script>


</body>
</html>