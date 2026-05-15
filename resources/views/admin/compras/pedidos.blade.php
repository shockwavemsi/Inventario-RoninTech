@extends('layouts.app')

@section('title', 'Compras')

@section('content')

<!-- ============ CONTENEDOR PRINCIPAL ============ -->
<div style="background: rgba(20, 20, 25, 0.5); backdrop-filter: blur(30px); border-radius: 16px; padding: 2rem; min-height: 80vh;">

    <!-- FLUJO PASOS -->
    <div id="flowSteps" style="display: flex; align-items: center; justify-content: center; gap: 1rem; margin-bottom: 3rem; flex-wrap: wrap;"></div>

    <!-- CONTENIDO PRINCIPAL -->
    <div id="mainContent" style="animation: fadeIn 0.3s ease-in;"></div>

</div>

<!-- ============ MODAL CREAR PEDIDO ============ -->
<div class="modal fade" id="modalPedido" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3);">

            <form action="{{ route('pedidos-compra.store') }}" method="POST" id="formPedido">
                @csrf

                <!-- HEADER -->
                <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946;">
                    <h5 class="modal-title" style="color: #e63946; font-weight: 700;">Crear Nuevo Pedido de Compra</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body" style="color: #f0f0f0;">

                    <!-- SECCIÓN 1: ENCABEZADO -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Nº Pedido</label>
                            <input type="text" name="numero_pedido" class="form-control" id="numeroPedido" readonly style="background: rgba(100, 100, 100, 0.3);">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Proveedor *</label>
                            <select name="proveedor_id" id="proveedorSelect" class="form-select" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;">
                                <option value="">Selecciona un proveedor...</option>
                                @foreach($proveedores as $prov)
                                    <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Fecha Pedido *</label>
                            <input type="date" name="fecha_pedido" class="form-control" value="{{ today() }}" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-bold">Estado</label>
                            <select name="estado" class="form-select" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;">
                                <option value="abierto">Abierto</option>
                                <option value="parcial">Parcial</option>
                                <option value="completo">Completo</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha Entrega Esperada</label>
                            <input type="date" name="fecha_entrega_esperada" class="form-control" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;">
                        </div>
                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3);">

                    <!-- SECCIÓN 2: TABLA DE PRODUCTOS -->
                    <h6 class="mb-3" style="color: #e63946; font-weight: 700;">Productos del Pedido</h6>

                    <div class="table-responsive mb-3">
                        <table class="table table-sm" id="tablaProductos">
                            <thead style="background: rgba(230, 57, 70, 0.15);">
                                <tr>
                                    <th style="color: #e63946; width: 8%">#</th>
                                    <th style="color: #e63946; width: 40%">Producto</th>
                                    <th style="color: #e63946; width: 15%">Cantidad</th>
                                    <th style="color: #e63946; width: 18%">Precio Unit.</th>
                                    <th style="color: #e63946; width: 14%">SUBTOTAL</th>
                                    <th style="color: #e63946; width: 5%">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="productosList"></tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-sm btn-success mb-4" onclick="window.CrearPedidoModal.agregarFila()">
                        <i class="bi bi-plus-lg"></i> Agregar Producto
                    </button>

                    <hr style="border-color: rgba(230, 57, 70, 0.3);">

                    <!-- SECCIÓN 3: TOTALES -->
                    <div class="row mb-4">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <div class="card" style="background: rgba(230, 57, 70, 0.1); border-color: rgba(230, 57, 70, 0.3);">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span style="color: #a0a0a0;">SUBTOTAL:</span>
                                        <strong id="subtotalDisplay" style="color: #f0f0f0;">0.00€</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3 pb-2" style="border-bottom: 1px solid rgba(230, 57, 70, 0.3);">
                                        <span style="color: #a0a0a0;">DESCUENTO:</span>
                                        <strong id="descuentoDisplay" style="color: #90ee90;">-0.00€</strong>
                                    </div>
                                    <div class="d-flex justify-content-between" style="font-size: 1.3rem;">
                                        <span style="color: #e63946; font-weight: 700;">TOTAL:</span>
                                        <strong id="totalDisplay" style="color: #e63946; font-size: 1.5rem;">0.00€</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Descuento (Opcional)</label>
                            <input type="number" name="descuento_cantidad" id="descuentoInput" class="form-control" value="0" min="0" step="0.01" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;">
                        </div>
                    </div>

                    <!-- CAMPOS OCULTOS -->
                    <input type="hidden" name="subtotal" id="subtotal_hidden">
                    <input type="hidden" name="total" id="total_hidden">

                    <hr style="border-color: rgba(230, 57, 70, 0.3);">

                    <!-- SECCIÓN 4: OBSERVACIONES -->
                    <label class="form-label fw-bold">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="3" placeholder="Notas sobre el pedido..." style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;"></textarea>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Guardar Pedido
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- ============ INYECTAR DATOS EN WINDOW ============ -->
<script>
    window.productosData = @json($productos);
    window.proveedoresData = @json($proveedores);
</script>

@endsection

@section('extra-js')

<!-- Script para cargar módulos ES6 -->
<script type="module">
    import ComprasApp from '/js/compras/compras.js';
</script>

@endsection