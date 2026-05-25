@extends('layouts.app')

@section('title', 'Sistema de Compras')

@section('content')

<!-- HEADER PERSONALIZADO -->

<div style="background: linear-gradient(to right, #0d0d0e 0%, #111111 100%); border-bottom: 2px solid #e63946; padding: 1.5rem 0; margin: -30px -30px 30px -30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);">

    <div style="max-width: 1200px; margin: 0 auto; padding: 0 30px; display: flex; justify-content: space-between; align-items: center;">

        <div style="display: flex; align-items: center; gap: 1rem;">

            <div style="width: 50px; height: 50px; background-color: #e63946; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 28px; color: white;">📦</div>

            <div>

                <h1 style="font-size: 1.8rem; font-weight: bold; color: #f0f0f0; margin: 0;">SISTEMA DE COMPRAS</h1>

                <p style="font-size: 0.75rem; color: #a0a0a0; margin: 0;">RoninTech - Inventario Software</p>

            </div>

        </div>

    </div>

</div>

<!-- FLOW SECTION -->

<div style="background-color: rgba(20, 20, 25, 0.65); border-bottom: 2px solid #e63946; padding: 2rem 0; margin: -30px -30px 30px -30px; backdrop-filter: blur(20px);">

    <div style="max-width: 1200px; margin: 0 auto; padding: 0 30px;">

        <p style="font-size: 0.875rem; color: #a0a0a0; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">Flujo de Compras</p>

        <div style="display: flex; justify-content: center; align-items: center; gap: 2rem; flex-wrap: wrap;" id="flowSteps"></div>

    </div>

</div>

<!-- MAIN CONTENT -->

<div id="mainContent" style="max-width: 1200px; margin: 0 auto;"></div>

<!-- ============ MODALES (Pedidos, Albaranes, Facturas) ============ -->

<!-- [TODO: Aquí van todos los modales - mantener igual que antes] -->

<!-- ============ MODAL CREAR PEDIDO ============ -->

<div class="modal fade" id="modalPedido" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">

    <div class="modal-dialog modal-xl">

        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3);">

            <form action="{{ route('pedidos-compra.store') }}" method="POST" id="formPedido">

                @csrf

                <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946;">

                    <h5 class="modal-title" style="color: #e63946; font-weight: 700;">Crear Nuevo Pedido de Compra</h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body" style="color: #f0f0f0; padding: 1.5rem;">

                    <div class="row mb-3" style="gap: 0.5rem;">

                        <div class="col">

                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Nº Pedido</label>

                            <input type="text" name="numero_pedido" class="form-control form-control-sm" id="numeroPedido" readonly style="background: rgba(100, 100, 100, 0.2); font-size: 0.9rem;">

                        </div>

                        <div class="col">

                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Proveedor *</label>

                            <select name="proveedor_id" id="proveedorSelect" class="form-select form-select-sm" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;">

                                <option value="">Selecciona...</option>

                                @foreach($proveedores as $prov)

                                    <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col">

                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Estado</label>

                            <div class="form-control form-control-sm" style="background: rgba(100, 100, 100, 0.2); font-size: 0.9rem; display: flex; align-items: center; color: #e63946; font-weight: bold;">

                                ABIERTO

                            </div>

                            <input type="hidden" name="estado" value="abierto">

                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-md-3">

                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Fecha Pedido</label>

                            <div class="form-control form-control-sm" style="background: rgba(100, 100, 100, 0.2); font-size: 0.9rem; display: flex; align-items: center; color: #f0f0f0; height: 38px;">

                                <strong>{{ today()->format('d/m/Y') }}</strong>

                            </div>

                            <input type="hidden" name="fecha_pedido" value="{{ today() }}">

                        </div>

                        <div class="col-md-3">

                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Fecha Entrega Esperada</label>

                            <input type="date" name="fecha_entrega_esperada" class="form-control form-control-sm" id="fechaEntrega" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;">

                        </div>

                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1rem 0;">

                    <h6 style="color: #e63946; font-weight: 700; font-size: 0.9rem; margin-bottom: 0.75rem;">Productos del Pedido</h6>

                    <div class="table-responsive mb-2" style="border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 6px;">

                        <table class="table table-sm" id="tablaProductos" style="margin: 0;">

                            <thead style="background: rgba(230, 57, 70, 0.15);">

                                <tr>

                                    <th style="color: #e63946; width: 8%; font-size: 0.8rem;">#</th>

                                    <th style="color: #e63946; width: 40%; font-size: 0.8rem;">Producto</th>

                                    <th style="color: #e63946; width: 12%; font-size: 0.8rem;">Cantidad</th>

                                    <th style="color: #e63946; width: 15%; font-size: 0.8rem;">Precio Unit.</th>

                                    <th style="color: #e63946; width: 15%; font-size: 0.8rem;">SUBTOTAL</th>

                                </tr>

                            </thead>

                            <tbody id="productosList"></tbody>

                        </table>

                    </div>

                    <button type="button" class="btn btn-sm btn-success mb-3" onclick="window.CrearPedidoModal.agregarFila()" style="font-size: 0.85rem;"><i class="bi bi-plus-lg"></i> Agregar Producto</button>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 0.75rem 0;">

                    <div class="row" style="gap: 1rem;">

                        <div class="col-md-3">

                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Descuento (%)</label>

                            <input type="number" id="descuentoInput" class="form-control form-control-sm" value="0" min="0" max="100" step="0.01" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;" onchange="window.CrearPedidoModal.calcularTotales()">

                        </div>

                        <div class="col-md-9">

                            <div style="background: rgba(230, 57, 70, 0.1); padding: 1rem; border-radius: 6px; border-left: 3px solid #e63946;">

                                <div class="d-flex justify-content-between align-items-center mb-2" style="font-size: 0.9rem;">

                                    <span style="color: #a0a0a0;">SUBTOTAL:</span>

                                    <strong id="subtotalDisplay" style="color: #f0f0f0;">0.00€</strong>

                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2" style="font-size: 0.9rem;">

                                    <span id="descuentoLabel" style="color: #a0a0a0;">DESCUENTO(0%):</span>

                                    <strong id="descuentoDisplay" style="color: #f0f0f0;">( - 0.00€ )</strong>

                                </div>

                                <div class="d-flex justify-content-between align-items-center" style="font-size: 1.1rem; padding-top: 0.75rem; border-top: 1px solid rgba(230, 57, 70, 0.3);">

                                    <span style="color: #e63946; font-weight: 700;">TOTAL:</span>

                                    <strong id="totalDisplay" style="color: #e63946; font-size: 1.3rem;">0.00€</strong>

                                </div>

                            </div>

                        </div>

                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1rem 0;">

                    <label class="form-label fw-bold" style="font-size: 0.85rem;">Observaciones</label>

                    <textarea name="observaciones" class="form-control form-control-sm" rows="2" placeholder="Notas sobre el pedido..." style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;"></textarea>

                    <input type="hidden" name="subtotal" id="subtotal_hidden" value="0">

                    <input type="hidden" name="descuento_porcentaje" id="descuento_porcentaje_hidden" value="0">

                    <input type="hidden" name="descuento_cantidad" id="descuento_cantidad_hidden" value="0">

                    <input type="hidden" name="total_general" id="total_hidden" value="0">

                </div>

                <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3);">

                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>

                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check-circle"></i> Guardar Pedido</button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- ============ MODAL CREAR ALBARÁN ============ -->

<div class="modal fade" id="modalAlbaran" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">

    <div class="modal-dialog modal-xl">

        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3);">

            <form action="{{ route('albaranes-compra.store') }}" method="POST" id="formAlbaran">

                @csrf

                <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946;">

                    <h5 class="modal-title" style="color: #e63946; font-weight: 700;">Crear Nuevo Albarán de Compra</h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body" style="color: #f0f0f0; padding: 1.5rem;">

                    <div class="row mb-3" style="gap: 0.5rem;">

                        <div class="col">

                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Nº Albarán</label>

                            <input type="text" name="numero_albaran" id="numeroAlbaran" class="form-control form-control-sm" readonly style="background: rgba(100, 100, 100, 0.2); font-size: 0.9rem;">

                        </div>

                        <div class="col">

                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Pedido *</label>

                            <div style="position: relative; display: inline-block; width: 100%;">

                                <input type="text" id="buscadorPedido" class="form-control form-control-sm" placeholder="Buscar pedido (Ej: PC-001)..." autocomplete="off" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem; width: 100%;">

                                <input type="hidden" name="pedido_compra_id" id="pedidoSelect" required>

                                <div id="listaPedidos" class="list-group" style="max-height: 150px; overflow-y: auto; display: none; position: absolute; width: 100%; z-index: 1000; background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3); border-radius: 6px; margin-top: 0.25rem; top: 100%; left: 0;"></div>

                            </div>

                        </div>

                        <div class="col">

                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Fecha Albarán *</label>

                            <div class="form-control form-control-sm" style="background: rgba(100, 100, 100, 0.2); font-size: 0.9rem; display: flex; align-items: center; color: #f0f0f0;">

                                <strong>{{ today()->format('d/m/Y') }}</strong>

                            </div>

                            <input type="hidden" name="fecha_albaran" value="{{ today() }}">

                        </div>

                        <div class="col">

                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Fecha Recepción</label>

                            <input type="date" name="fecha_recepcion" id="fechaRecepcion" class="form-control form-control-sm" value="{{ today() }}" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;">

                        </div>

                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1rem 0;">

                    <h6 style="color: #e63946; font-weight: 700; font-size: 0.9rem; margin-bottom: 0.75rem;">Productos a Recibir</h6>

                    <div class="table-responsive mb-2" style="border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 6px;">

                        <table class="table table-sm" id="tablaProductos" style="margin: 0;">

                            <thead style="background: rgba(230, 57, 70, 0.15);">

                                <tr>

                                    <th style="color: #e63946; width: 30%; font-size: 0.8rem;">Producto</th>

                                    <th style="color: #e63946; width: 15%; font-size: 0.8rem;">Cant. Recibida *</th>

                                    <th style="color: #e63946; width: 15%; font-size: 0.8rem;">Estado</th>

                                </tr>

                            </thead>

                            <tbody id="productosListAlbaran"></tbody>

                        </table>

                    </div>

                    <p style="color: #a0a0a0; font-size: 0.8rem; margin-top: 0.5rem;">✓ La cantidad faltante se calcula automáticamente</p>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1rem 0;">

                    <label class="form-label fw-bold" style="font-size: 0.85rem;">Observaciones</label>

                    <textarea name="observaciones" class="form-control form-control-sm" rows="2" placeholder="Notas sobre la recepción..." style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;"></textarea>

                    <input type="hidden" name="estado_general" id="estado_hidden" value="recibido">

                </div>

                <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3);">

                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>

                    <button type="submit" class="btn btn-primary btn-sm" style="background: #e63946; border: none;">✅ Guardar Albarán</button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- ============ MODAL CREAR FACTURA - CON FORMAS DE PAGO ============ -->

<div class="modal fade" id="modalFactura" tabindex="-1" role="dialog">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3);">

            <div class="modal-header" style="border-bottom: 2px solid #e63946; background: rgba(230, 57, 70, 0.15); padding: 1.25rem;">

                <h5 class="modal-title fw-bold" style="color: #e63946; margin: 0; font-size: 1.3rem;">

                    ➕ CREAR NUEVA FACTURA

                </h5>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

            </div>

            <div class="modal-body" style="color: #f0f0f0; max-height: 80vh; overflow-y: auto; padding: 2rem;">

                <form id="formFactura" method="POST" action="{{ route('facturas-compra.store') }}">

                    @csrf

                    <div class="col" style="position: relative;">

                        <label class="form-label fw-bold" style="font-size: 0.85rem;">Albarán *</label>

                        <input type="text" id="buscadorAlbaran" class="form-control form-control-sm" placeholder="Buscar albarán (Ej: ALB-COMP-001)..." autocomplete="off" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem; width: 100%;">

                        <div id="listaAlbaranes" class="list-group" style="max-height: 150px; overflow-y: auto; display: none; position: absolute; width: 100%; z-index: 1000; background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3); border-radius: 6px; margin-top: 0.25rem; top: 100%; left: 0;"></div>

                        <input type="hidden" id="albaranSelect" name="albaran_compra_id" required>

                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 2rem 0;">

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(230, 57, 70, 0.2);">

                        <div>

                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">

                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>

                                <h6 style="margin: 0; color: #e63946; font-weight: 700; font-size: 0.9rem; text-transform: uppercase;">Información General</h6>

                            </div>

                            <div style="display: grid; gap: 1rem;">

                                <div>

                                    <div style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; margin-bottom: 0.4rem; font-weight: 600;">Proveedor</div>

                                    <div style="color: #f0f0f0; font-weight: 500; font-size: 1rem;" id="detalleProveedor">—</div>

                                </div>

                                <div>

                                    <div style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; margin-bottom: 0.4rem; font-weight: 600;">Albarán</div>

                                    <div style="color: #e63946; font-weight: 600; font-size: 1rem;" id="detalleAlbaran">—</div>

                                </div>

                                <div>

                                    <div style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; margin-bottom: 0.4rem; font-weight: 600;">Pedido</div>

                                    <div style="color: #f0f0f0; font-weight: 500;" id="detallePedido">—</div>

                                </div>

                            </div>

                        </div>

                        <div>

                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">

                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><line x1="12" y1="2" x2="12" y2="22"></line><path d="M17 5H9.5a1.5 1.5 0 0 0-1.5 1.5v12a1.5 1.5 0 0 0 1.5 1.5H17"></path><path d="M7 12l4-4 4 4"></path></svg>

                                <h6 style="margin: 0; color: #e63946; font-weight: 700; font-size: 0.9rem; text-transform: uppercase;">Estado y Totales</h6>

                            </div>

                            <div style="display: grid; gap: 1rem;">

                                <div>

                                    <div style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; margin-bottom: 0.4rem; font-weight: 600;">Nº Factura</div>

                                    <input type="text" name="numero_factura" id="numeroFactura" class="form-control form-control-sm" readonly

                                        style="background: rgba(100, 100, 100, 0.3); border-color: rgba(230, 57, 70, 0.3); color: #e63946; font-weight: bold;">

                                </div>

                                <div>

                                    <div style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; margin-bottom: 0.4rem; font-weight: 600;">Fecha Factura</div>

                                    <div class="form-control form-control-sm" style="background: rgba(100, 100, 100, 0.3); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; display: flex; align-items: center;">

                                        <strong id="fechaFacturaDisplay">—</strong>

                                    </div>

                                    <input type="hidden" name="fecha_factura" id="fechaFactura">

                                </div>

                                <div>

                                    <div style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; margin-bottom: 0.4rem; font-weight: 600;">Fecha Vencimiento</div>

                                    <div class="form-control form-control-sm" style="background: rgba(100, 100, 100, 0.3); border-color: rgba(230, 57, 70, 0.3); color: #90ee90; display: flex; align-items: center; font-weight: bold;">

                                        <strong id="fechaVencimientoDisplay">—</strong>

                                    </div>

                                    <input type="hidden" name="fecha_vencimiento" id="fechaVencimiento">

                                </div>

                            </div>

                            <div style="background: rgba(230, 57, 70, 0.1); padding: 1rem; border-radius: 6px; border-left: 3px solid #e63946; margin-top: 1.5rem;">

                                <div style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; margin-bottom: 0.5rem; font-weight: 600;">TOTAL</div>

                                <div style="color: #e63946; font-weight: 700; font-size: 1.75rem;" id="detalleTotal">0.00€</div>

                            </div>

                        </div>

                    </div>

                    <div style="margin-bottom: 2rem;">

    <h6 style="color: #e63946; font-weight: 700; font-size: 0.95rem; margin-bottom: 1rem; text-transform: uppercase;">📋 Líneas a Facturar</h6>

    <div style="overflow-x: auto; border-radius: 8px; border: 1px solid rgba(230, 57, 70, 0.2);">

        <table style="width: 100%; border-collapse: collapse;">

            <thead>

                <tr style="background: rgba(230, 57, 70, 0.15);">

                    <th style="padding: 0.75rem; color: #e63946; text-align: center; font-weight: 700; font-size: 0.875rem;">#</th>

                    <th style="padding: 0.75rem; color: #e63946; text-align: left; font-weight: 700; font-size: 0.875rem;">PRODUCTO</th>

                    <th style="padding: 0.75rem; color: #e63946; text-align: center; font-weight: 700; font-size: 0.875rem;">CANT. RECIBIDA</th>

                </tr>

            </thead>

            <tbody id="productosFacturaList">

                <tr>

                    <td colspan="3" style="text-align: center; padding: 1.5rem; color: #a0a0a0;">

                        Selecciona un albarán para cargar productos

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 2rem 0;">

                    <div style="margin-bottom: 2rem;">

                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">

                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>

                            <h6 style="margin: 0; color: #e63946; font-weight: 700; font-size: 0.95rem; text-transform: uppercase;">💳 Formas de Pago</h6>

                        </div>

                        <div style="overflow-x: auto; border-radius: 8px; border: 1px solid rgba(230, 57, 70, 0.2); margin-bottom: 1rem;">

                            <table style="width: 100%; border-collapse: collapse;">

                                <thead>

                                    <tr style="background: rgba(230, 57, 70, 0.15);">

                                        <th style="padding: 0.75rem; color: #e63946; text-align: left; font-weight: 700; font-size: 0.875rem;">MÉTODO</th>

                                        <th style="padding: 0.75rem; color: #e63946; text-align: left; font-weight: 700; font-size: 0.875rem;">BANCO</th>

                                        <th style="padding: 0.75rem; color: #e63946; text-align: right; font-weight: 700; font-size: 0.875rem;">MONTO</th>

                                        <th style="padding: 0.75rem; color: #e63946; text-align: center; font-weight: 700; font-size: 0.875rem;">FECHA</th>

                                        <th style="padding: 0.75rem; color: #e63946; text-align: center; font-weight: 700; font-size: 0.875rem;">REFERENCIA</th>

                                        <th style="padding: 0.75rem; color: #e63946; text-align: center; font-weight: 700; font-size: 0.875rem;">ESTADO</th>

                                    </tr>

                                </thead>

                                <tbody id="pagosFacturaList">

                                    <tr>

                                        <td colspan="6" style="text-align: center; padding: 1.5rem; color: #a0a0a0;">

                                            No hay métodos de pago agregados

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="window.CrearFacturaModal.agregarFilaPago()">

                            ➕ Agregar Método de Pago

                        </button>

                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 2rem 0;">

                    <div style="padding: 1rem; background: rgba(230, 57, 70, 0.05); border-radius: 8px; border-left: 3px solid #e63946; margin-bottom: 1rem;">

                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">

                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>

                            <div style="font-size: 0.875rem; font-weight: 700; color: #e63946; text-transform: uppercase;">Observaciones</div>

                        </div>

                        <textarea name="observaciones" class="form-control form-control-sm" rows="3" placeholder="Notas sobre la factura..."

                            style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;"></textarea>

                    </div>

                    <input type="hidden" name="estado" value="abierta">

                </form>

            </div>

            <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3); padding: 1.25rem;">

                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">

                    ✕ Cancelar

                </button>

                <button type="submit" form="formFactura" class="btn btn-danger btn-sm">

                    ✓ Crear Factura

                </button>

            </div>

        </div>

    </div>

</div>

<!-- ✅ INYECTAR DATOS GLOBALES AL INICIO -->

<script>

    window.ultimoPedidoId = @json($ultimoPedidoId ?? 0);

    window.ultimoAlbaranId = @json($ultimoAlbaranId ?? 0);

    window.ultimoFacturaId = @json($ultimoFacturaId ?? 0);

    window.pedidosData = @json($pedidos ?? []);

    window.albaranesData = @json($albaranes ?? []);

    window.proveedores = @json($proveedores ?? []);

    window.productos = @json($productos ?? []);

    console.log('✅ Datos globales:', {

        ultimoPedidoId: window.ultimoPedidoId,

        pedidos: window.pedidosData?.length,

        albaranes: window.albaranesData?.length

    });

</script>

@push('scripts')

    <script src="{{secure_asset('js/compras/modales/crear-factura-modal.js') }}"></script>

@endpush

@endsection
