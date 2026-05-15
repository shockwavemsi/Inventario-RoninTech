<!-- ============ MODAL CREAR VENTA ============ -->
<div class="modal fade" id="modalVenta" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3);">
            <form action="{{ route('ventas.store') }}" method="POST" id="formVenta">
                @csrf

                <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946;">
                    <h5 class="modal-title" style="color: #e63946; font-weight: 700;">➕ Nueva Venta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="color: #f0f0f0; padding: 1.5rem; max-height: 80vh; overflow-y: auto;">

                    <!-- NÚMERO Y CLIENTE -->
                    <div class="row mb-3" style="gap: 0.5rem;">
                        <div class="col-md-3">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Nº Venta</label>
                            <input type="text" name="numero_factura" id="numeroVenta" class="form-control form-control-sm" readonly style="background: rgba(100, 100, 100, 0.2); font-size: 0.9rem;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Cliente *</label>
                            <input type="text" name="cliente" id="cliente" class="form-control form-control-sm" placeholder="Nombre del cliente" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Documento (Opcional)</label>
                            <input type="text" name="cliente_documento" id="clienteDocumento" class="form-control form-control-sm" placeholder="DNI/NIF/Pasaporte" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;">
                        </div>
                    </div>

                    <!-- MÉTODO PAGO -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Método de Pago *</label>
                            <select name="metodo_pago_id" id="metodoPago" class="form-select form-select-sm" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;">
                                <option value="">Selecciona...</option>
                                @foreach($metodosPago as $metodo)
                                    <option value="{{ $metodo->id }}">{{ $metodo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Estado</label>
                            <select name="estado" id="estado" class="form-select form-select-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;">
                                <option value="pendiente">Pendiente</option>
                                <option value="completada" selected>Completada</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>
                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1rem 0;">

                    <!-- BUSCADOR DE PRODUCTOS -->
                    <h6 style="color: #e63946; font-weight: 700; font-size: 0.9rem; margin-bottom: 0.75rem;">🔍 Buscar Productos</h6>
                    <div style="position: relative; margin-bottom: 1rem;">
                        <input type="text" id="buscadorProductos" class="form-control form-control-sm" placeholder="Escribe nombre, marca, modelo..." autocomplete="off" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;">
                        <div id="listaProductos" class="list-group" style="max-height: 200px; overflow-y: auto; display: none; position: absolute; width: 100%; z-index: 1000; background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3); border-radius: 6px; margin-top: 0.25rem; top: 100%; left: 0;"></div>
                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1rem 0;">

                    <!-- TABLA PRODUCTOS -->
                    <h6 style="color: #e63946; font-weight: 700; font-size: 0.9rem; margin-bottom: 0.75rem;">📦 Líneas de Venta</h6>
                    <div class="table-responsive mb-2" style="border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 6px;">
                        <table class="table table-sm" id="tablaLineas" style="margin: 0;">
                            <thead style="background: rgba(230, 57, 70, 0.15);">
                                <tr>
                                    <th style="color: #e63946; width: 5%; font-size: 0.8rem;">#</th>
                                    <th style="color: #e63946; width: 30%; font-size: 0.8rem;">Producto</th>
                                    <th style="color: #e63946; width: 10%; font-size: 0.8rem;">Stock</th>
                                    <th style="color: #e63946; width: 10%; font-size: 0.8rem;">Cantidad</th>
                                    <th style="color: #e63946; width: 15%; font-size: 0.8rem;">Precio Unit.</th>
                                    <th style="color: #e63946; width: 15%; font-size: 0.8rem;">Subtotal</th>
                                    <th style="color: #e63946; width: 8%; font-size: 0.8rem;">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="lineasList"></tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-sm btn-success mb-2" onclick="window.CrearVentaModal.agregarFila()" style="font-size: 0.85rem;">
                        ➕ Agregar Línea
                    </button>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1rem 0;">

                    <!-- TOTALES -->
                    <div style="background: rgba(230, 57, 70, 0.1); padding: 1rem; border-radius: 6px; border-left: 3px solid #e63946;">
                        <div class="d-flex justify-content-between" style="margin-bottom: 0.5rem; font-size: 0.9rem;">
                            <span style="color: #a0a0a0;">SUBTOTAL:</span>
                            <strong id="subtotalDisplay" style="color: #f0f0f0;">0.00€</strong>
                        </div>
                        <div class="d-flex justify-content-between" style="margin-bottom: 0.5rem; font-size: 0.9rem;">
                            <span style="color: #a0a0a0;">IVA (21%):</span>
                            <strong id="ivaDisplay" style="color: #f0f0f0;">0.00€</strong>
                        </div>
                        <div class="d-flex justify-content-between" style="font-size: 1.1rem; padding-top: 0.75rem; border-top: 1px solid rgba(230, 57, 70, 0.3);">
                            <span style="color: #e63946; font-weight: 700;">TOTAL:</span>
                            <strong id="totalDisplay" style="color: #e63946; font-size: 1.3rem;">0.00€</strong>
                        </div>
                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1rem 0;">

                    <!-- OBSERVACIONES -->
                    <label class="form-label fw-bold" style="font-size: 0.85rem;">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" class="form-control form-control-sm" rows="2" placeholder="Notas sobre la venta..." style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;"></textarea>

                    <!-- Hidden input para guardar líneas -->
                    <input type="hidden" name="lineas" id="lineasJson" value="[]">

                </div>

                <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3);">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger btn-sm">✓ Guardar Venta</button>
                </div>

            </form>
        </div>
    </div>
</div>