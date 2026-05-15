<!-- ============ MODAL CREAR FACTURA VENTA ============ -->
<div class="modal fade" id="modalFacturaVenta" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3);">
            <form action="{{ route('ventas-factura.store') }}" method="POST" id="formFacturaVenta">
                @csrf

                <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946;">
                    <h5 class="modal-title" style="color: #e63946; font-weight: 700;">🧾 Nueva Factura de Venta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="color: #f0f0f0; padding: 1.5rem; max-height: 80vh; overflow-y: auto;">

                    <!-- NÚMERO Y VENTA -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Nº Factura</label>
                            <input type="text" name="numero_factura" id="numeroFactura" class="form-control form-control-sm" readonly style="background: rgba(100, 100, 100, 0.2); font-size: 0.9rem;">
                        </div>
                        <div class="col-md-9">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Venta *</label>
                            <select name="venta_id" id="ventaSelect" class="form-select form-select-sm" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;">
                                <option value="">Selecciona una venta...</option>
                            </select>
                        </div>
                    </div>

                    <!-- INFORMACIÓN VENTA -->
                    <div class="row mb-3" style="background: rgba(230, 57, 70, 0.1); padding: 1rem; border-radius: 6px; border-left: 3px solid #e63946;">
                        <div class="col-md-4">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Cliente:</label>
                            <p id="detalleCliente" style="color: #f0f0f0; margin: 0;">—</p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Fecha Venta:</label>
                            <p id="detalleFechaVenta" style="color: #f0f0f0; margin: 0;">—</p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Total Venta:</label>
                            <p id="detalleTotalVenta" style="color: #e63946; font-weight: 600; margin: 0;">—</p>
                        </div>
                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1rem 0;">

                    <!-- FECHAS FACTURA -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Fecha Factura *</label>
                            <input type="date" name="fecha_factura" id="fechaFactura" class="form-control form-control-sm" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Fecha Vencimiento</label>
                            <input type="date" name="fecha_vencimiento" id="fechaVencimiento" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;">
                        </div>
                    </div>

                    <!-- LÍNEAS (desde venta) -->
                    <h6 style="color: #e63946; font-weight: 700; font-size: 0.9rem; margin-bottom: 0.75rem;">📦 Líneas de Venta</h6>
                    <div class="table-responsive mb-2" style="border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 6px;">
                        <table class="table table-sm" id="tablaLineasFactura" style="margin: 0;">
                            <thead style="background: rgba(230, 57, 70, 0.15);">
                                <tr>
                                    <th style="color: #e63946; font-size: 0.8rem;">Producto</th>
                                    <th style="color: #e63946; width: 10%; font-size: 0.8rem;">Cantidad</th>
                                    <th style="color: #e63946; width: 15%; font-size: 0.8rem;">Precio Unit.</th>
                                    <th style="color: #e63946; width: 15%; font-size: 0.8rem;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="lineasFacturaList" style="color: #f0f0f0; font-size: 0.85rem;"></tbody>
                        </table>
                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1rem 0;">

                    <!-- TOTALES (desde venta) -->
                    <div style="background: rgba(230, 57, 70, 0.1); padding: 1rem; border-radius: 6px; border-left: 3px solid #e63946;">
                        <div class="d-flex justify-content-between" style="margin-bottom: 0.5rem; font-size: 0.9rem;">
                            <span style="color: #a0a0a0;">SUBTOTAL:</span>
                            <strong id="subtotalFacturaDisplay" style="color: #f0f0f0;">0.00€</strong>
                        </div>
                        <div class="d-flex justify-content-between" style="margin-bottom: 0.5rem; font-size: 0.9rem;">
                            <span style="color: #a0a0a0;">IVA (21%):</span>
                            <strong id="ivaFacturaDisplay" style="color: #f0f0f0;">0.00€</strong>
                        </div>
                        <div class="d-flex justify-content-between" style="font-size: 1.1rem; padding-top: 0.75rem; border-top: 1px solid rgba(230, 57, 70, 0.3);">
                            <span style="color: #e63946; font-weight: 700;">TOTAL:</span>
                            <strong id="totalFacturaDisplay" style="color: #e63946; font-size: 1.3rem;">0.00€</strong>
                        </div>
                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1rem 0;">

                    <!-- OBSERVACIONES -->
                    <label class="form-label fw-bold" style="font-size: 0.85rem;">Observaciones</label>
                    <textarea name="observaciones" id="observacionesFactura" class="form-control form-control-sm" rows="2" placeholder="Notas sobre la factura..." style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;"></textarea>

                </div>

                <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3);">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger btn-sm">✓ Guardar Factura</button>
                </div>

            </form>
        </div>
    </div>
</div>