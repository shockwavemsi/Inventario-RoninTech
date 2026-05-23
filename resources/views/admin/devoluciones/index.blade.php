@extends('layouts.app')

@section('title', 'Devoluciones de Ventas')

@section('content')

<!-- HEADER PERSONALIZADO -->

<div style="background: linear-gradient(to right, #0d0d0e 0%, #111111 100%); border-bottom: 2px solid #d32f2f; padding: 1.5rem 0; margin: -30px -30px 30px -30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);">

    <div style="max-width: 1200px; margin: 0 auto; padding: 0 30px; display: flex; justify-content: space-between; align-items: center;">

        <div style="display: flex; align-items: center; gap: 1rem;">

            <div style="width: 50px; height: 50px; background-color: #d32f2f; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 28px; color: white;">📦</div>

            <div>

                <h1 style="font-size: 1.8rem; font-weight: bold; color: #f0f0f0; margin: 0;">DEVOLUCIONES DE VENTAS</h1>

                <p style="font-size: 0.75rem; color: #a0a0a0; margin: 0;">RoninTech - Gestión de Devoluciones de Productos Vendidos</p>

            </div>

        </div>

    </div>

</div>

<!-- ESTADÍSTICAS DINÁMICAS -->

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; max-width: 1200px; margin: 2rem auto; padding: 0 30px;">

    <!-- WIDGET 1: TOTAL DEVOLUCIONES -->

    <div class="widget-stat" id="widget-total" style="background: rgba(20, 20, 25, 0.65); border: 1px solid rgba(211, 47, 47, 0.2); border-radius: 8px; padding: 1.5rem; transition: all 0.3s ease;">

        <div style="display: flex; align-items: center; gap: 1rem;">

            <div style="width: 50px; height: 50px; background: rgba(211, 47, 47, 0.15); border-radius: 8px; display: flex; align-items: center; justify-content: center;">

                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#d32f2f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                    <path d="M6 9l6-6 6 6M5 9h14a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2z"></path>

                </svg>

            </div>

            <div>

                <span style="color: #a0a0a0; font-size: 0.85rem; display: block;">Total Devoluciones</span>

                <strong class="stat-value" id="stat-total" style="color: #d32f2f; font-size: 1.8rem; display: block;">{{ $totalDevoluciones }}</strong>

            </div>

        </div>

    </div>

    <!-- WIDGET 2: EN REVISIÓN / PENDIENTES -->

    <div class="widget-stat" id="widget-pendientes" style="background: rgba(20, 20, 25, 0.65); border: 1px solid rgba(211, 47, 47, 0.2); border-radius: 8px; padding: 1.5rem; transition: all 0.3s ease;">

        <div style="display: flex; align-items: center; gap: 1rem;">

            <div style="width: 50px; height: 50px; background: rgba(255, 107, 107, 0.15); border-radius: 8px; display: flex; align-items: center; justify-content: center;">

                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ff6b6b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                    <circle cx="12" cy="12" r="10"></circle>

                    <polyline points="12 6 12 12 16 14"></polyline>

                </svg>

            </div>

            <div>

                <span style="color: #a0a0a0; font-size: 0.85rem; display: block;">En Revisión</span>

                <strong class="stat-value" id="stat-pendientes" style="color: #ff6b6b; font-size: 1.8rem; display: block;">{{ $pendientes }}</strong>

            </div>

        </div>

    </div>

    <!-- WIDGET 3: COMPLETADAS -->

    <div class="widget-stat" id="widget-completadas" style="background: rgba(20, 20, 25, 0.65); border: 1px solid rgba(40, 167, 69, 0.2); border-radius: 8px; padding: 1.5rem; transition: all 0.3s ease;">

        <div style="display: flex; align-items: center; gap: 1rem;">

            <div style="width: 50px; height: 50px; background: rgba(40, 167, 69, 0.15); border-radius: 8px; display: flex; align-items: center; justify-content: center;">

                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                    <polyline points="20 6 9 17 4 12"></polyline>

                </svg>

            </div>

            <div>

                <span style="color: #a0a0a0; font-size: 0.85rem; display: block;">Completadas</span>

                <strong class="stat-value" id="stat-completadas" style="color: #28a745; font-size: 1.8rem; display: block;">{{ $completadas }}</strong>

            </div>

        </div>

    </div>

    <!-- WIDGET 4: VALOR TOTAL -->

    <div class="widget-stat" id="widget-valor" style="background: rgba(20, 20, 25, 0.65); border: 1px solid rgba(211, 47, 47, 0.2); border-radius: 8px; padding: 1.5rem; transition: all 0.3s ease;">

        <div style="display: flex; align-items: center; gap: 1rem;">

            <div style="width: 50px; height: 50px; background: rgba(211, 47, 47, 0.15); border-radius: 8px; display: flex; align-items: center; justify-content: center;">

                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#d32f2f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>

                    <line x1="1" y1="10" x2="23" y2="10"></line>

                </svg>

            </div>

            <div>

                <span style="color: #a0a0a0; font-size: 0.85rem; display: block;">Valor Total</span>

                <strong class="stat-value" id="stat-valor" style="color: #28a745; font-size: 1.8rem; display: block;">€{{ number_format($valorTotal, 2) }}</strong>

            </div>

        </div>

    </div>

</div>

<!-- CONTENIDO PRINCIPAL -->

<div style="max-width: 1200px; margin: 2rem auto; padding: 0 30px;">

    <div style="background: rgba(20, 20, 25, 0.65); border: 1px solid rgba(211, 47, 47, 0.2); border-radius: 8px; padding: 1.5rem; backdrop-filter: blur(20px);">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">

            <h5 style="color: #d32f2f; font-weight: 700; margin: 0;">📋 Devoluciones Registradas</h5>

            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrearDevolucion">

                ➕ Nueva Devolución

            </button>

        </div>

        <div style="overflow-x: auto; border-radius: 8px;">

            <table class="table table-dark table-hover" style="margin: 0; background-color: rgba(20, 20, 25, 0.8);">

                <thead style="background: rgba(211, 47, 47, 0.15);">

                    <tr>

                        <th style="color: #d32f2f; font-weight: 700;">Nº Devolución</th>

                        <th style="color: #d32f2f; font-weight: 700;">Cliente</th>

                        <th style="color: #d32f2f; font-weight: 700;">Fecha</th>

                        <th style="color: #d32f2f; font-weight: 700;">Producto</th>

                        <th style="color: #d32f2f; font-weight: 700;">Monto</th>

                        <th style="color: #d32f2f; font-weight: 700;">Estado</th>

                        <th style="color: #d32f2f; font-weight: 700;">Acciones</th>

                    </tr>

                </thead>

                <tbody id="tablaDevoluciones">

                    @forelse($devoluciones as $dev)

                    <tr>

                        <td>{{ $dev['numero'] }}</td>

                        <td>{{ $dev['cliente'] }}</td>

                        <td>{{ $dev['fecha'] }}</td>

                        <td>{{ $dev['producto'] }}</td>

                        <td>€{{ number_format($dev['total'], 2) }}</td>

                        <td>

                            <span class="badge bg-{{ $dev['estado'] == 'completada' ? 'success' : 'danger' }}">

                                {{ ucfirst($dev['estado']) }}

                            </span>

                        </td>

                        <td>

                            <button class="btn btn-sm btn-info" onclick="verDevolucion({{ $dev['id'] }})">Ver</button>

                            @if($dev['estado'] == 'pendiente')

                                <button class="btn btn-sm btn-success" onclick="cambiarEstado({{ $dev['id'] }}, 'completada')">Completar</button>

                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr><td colspan="7" style="text-align: center; color: #a0a0a0; padding: 2rem;">📭 Sin devoluciones registradas</td></tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- ============ MODAL CREAR DEVOLUCIÓN ============ -->

<div class="modal fade" id="modalCrearDevolucion" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">

    <div class="modal-dialog modal-lg">

        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(211, 47, 47, 0.3);">

            <form id="formDevolucion" method="POST" action="{{ route('devoluciones.store') }}">

                @csrf

                <div class="modal-header" style="background: rgba(211, 47, 47, 0.15); border-bottom: 2px solid #d32f2f;">

                    <h5 class="modal-title" style="color: #d32f2f; font-weight: 700;">➕ Nueva Devolución de Venta</h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body" style="color: #f0f0f0; padding: 1.5rem; max-height: 80vh; overflow-y: auto;">

                    <!-- SELECCIONAR VENTA -->

                    <h6 style="color: #d32f2f; font-weight: 700; margin-bottom: 1rem;">📅 Seleccionar Venta</h6>

                    <div class="mb-3">

                        <label class="form-label fw-bold" style="font-size: 0.85rem; color: #f0f0f0;">Venta *</label>

                        <input type="text" id="buscadorVenta" class="form-control form-control-sm" placeholder="Busca por número, cliente o monto..." autocomplete="off" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(211, 47, 47, 0.3); color: #f0f0f0; font-size: 0.9rem;">

                        <input type="hidden" name="venta_id" id="ventaId">

                        <div id="listaVentas" style="display: none; position: absolute; background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(211, 47, 47, 0.3); border-radius: 6px; max-width: 400px; max-height: 200px; overflow-y: auto; z-index: 1000; margin-top: 0.25rem;"></div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-md-6">

                            <label class="form-label fw-bold" style="font-size: 0.85rem; color: #f0f0f0;">Cliente</label>

                            <input type="text" id="clienteVenta" class="form-control form-control-sm" readonly style="background: rgba(100, 100, 100, 0.2); font-size: 0.9rem; color: #a0a0a0;">

                        </div>

                        <div class="col-md-6">

                            <label class="form-label fw-bold" style="font-size: 0.85rem; color: #f0f0f0;">Total Venta</label>

                            <input type="text" id="totalVenta" class="form-control form-control-sm" readonly style="background: rgba(100, 100, 100, 0.2); font-size: 0.9rem; color: #28a745;">

                        </div>

                    </div>

                    <hr style="border-color: rgba(211, 47, 47, 0.3); margin: 1rem 0;">

                    <!-- PRODUCTOS -->

                    <h6 style="color: #d32f2f; font-weight: 700; margin-bottom: 1rem;">📦 Selecciona Productos para Devolver</h6>

                    <div class="table-responsive mb-3" style="border: 1px solid rgba(211, 47, 47, 0.2); border-radius: 6px;">

                        <table class="table table-sm" style="margin: 0; background-color: rgba(20, 20, 25, 0.8);">

                            <thead style="background: rgba(211, 47, 47, 0.15);">

                                <tr>

                                    <th style="color: #d32f2f; width: 5%; text-align: center; font-size: 0.8rem;">✓</th>

                                    <th style="color: #d32f2f; font-size: 0.8rem;">Producto</th>

                                    <th style="color: #d32f2f; width: 10%; text-align: center; font-size: 0.8rem;">Cant.Orig</th>

                                    <th style="color: #d32f2f; width: 12%; text-align: center; font-size: 0.8rem;">Cant.Dev</th>

                                    <th style="color: #d32f2f; width: 12%; text-align: right; font-size: 0.8rem;">Precio Base</th>

                                    <th style="color: #d32f2f; width: 13%; text-align: right; font-size: 0.8rem;">Subtotal</th>

                                </tr>

                            </thead>

                            <tbody id="tablaProd" style="color: #f0f0f0; font-size: 0.875rem;">

                                <tr>

                                    <td colspan="6" style="text-align: center; padding: 2rem; color: #a0a0a0;">Selecciona una venta</td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                    <hr style="border-color: rgba(211, 47, 47, 0.3); margin: 1rem 0;">

                    <!-- DETALLES -->

                    <h6 style="color: #d32f2f; font-weight: 700; margin-bottom: 1rem;">📄 Detalles de Devolución</h6>

                    <div class="mb-3">

                        <label class="form-label fw-bold" style="font-size: 0.85rem; color: #f0f0f0;">Motivo *</label>

                        <textarea name="motivo" id="motivo" class="form-control form-control-sm" rows="3" placeholder="Describe el motivo..." required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(211, 47, 47, 0.3); color: #f0f0f0; font-size: 0.9rem;"></textarea>

                    </div>

                    <div class="mb-3">

                        <label class="form-label fw-bold" style="font-size: 0.85rem; color: #f0f0f0;">Estado *</label>

                        <select name="estado" id="estado" class="form-select form-select-sm" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(211, 47, 47, 0.3); color: #f0f0f0; font-size: 0.9rem;">

                            <option value="pendiente" style="background: rgba(20, 20, 25); color: #f0f0f0;">Pendiente</option>

                            <option value="completada" selected style="background: rgba(20, 20, 25); color: #f0f0f0;">Completada</option>

                        </select>

                    </div>

                    <div style="background: rgba(211, 47, 47, 0.1); padding: 1rem; border-radius: 6px; border-left: 3px solid #d32f2f;">

                        <div class="d-flex justify-content-between" style="font-size: 0.9rem; margin-bottom: 0.5rem;">

                            <span style="color: #a0a0a0;">TOTAL CON IVA (ORIGINAL):</span>

                            <strong id="totalConIva" style="color: #f0f0f0;">€0.00</strong>

                        </div>

                        <div class="d-flex justify-content-between" style="font-size: 0.9rem; margin-bottom: 0.5rem;">

                            <span style="color: #a0a0a0;">IVA (21%) - RETENEMOS:</span>

                            <strong id="montoIva" style="color: #ff6b6b;">€0.00</strong>

                        </div>

                        <div class="d-flex justify-content-between" style="font-size: 1rem; padding-top: 0.75rem; border-top: 1px solid rgba(211, 47, 47, 0.3);">

                            <span style="color: #d32f2f; font-weight: 700;">A DEVOLVER (SIN IVA):</span>

                            <strong id="totalDevolver" style="color: #28a745; font-size: 1.3rem;">€0.00</strong>

                        </div>

                    </div>

                    <input type="hidden" name="productos_json" id="productosJson" value="[]">

                    <input type="hidden" name="total_devuelto" id="totalInput" value="0">

                </div>

                <div class="modal-footer" style="border-top: 1px solid rgba(211, 47, 47, 0.3);">

                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>

                    <button type="submit" class="btn btn-danger btn-sm">✓ Guardar Devolución</button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- ============ MODAL VER DEVOLUCIÓN ============ -->

<div class="modal fade" id="modalVerDevolucion" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">

    <div class="modal-dialog modal-lg">

        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(211, 47, 47, 0.3);">

            <div class="modal-header" style="background: rgba(211, 47, 47, 0.15); border-bottom: 2px solid #d32f2f;">

                <h5 class="modal-title" style="color: #d32f2f; font-weight: 700;">📋 Detalles de la Devolución</h5>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

            </div>

            <div class="modal-body" style="color: #f0f0f0; padding: 1.5rem; max-height: 80vh; overflow-y: auto;">

                <div class="row mb-3">

                    <div class="col-md-6">

                        <h6 style="color: #d32f2f; font-weight: 700; margin-bottom: 1rem;">📌 Información</h6>

                        <div style="background: rgba(211, 47, 47, 0.1); padding: 1rem; border-radius: 6px; border-left: 3px solid #d32f2f;">

                            <div style="margin-bottom: 0.5rem;">

                                <span style="color: #a0a0a0; font-size: 0.85rem;">Código:</span>

                                <strong id="verCodigo" style="color: #d32f2f; display: block;">DEV-0001</strong>

                            </div>

                            <div style="margin-bottom: 0.5rem;">

                                <span style="color: #a0a0a0; font-size: 0.85rem;">Cliente:</span>

                                <strong id="verCliente" style="color: #f0f0f0; display: block;">—</strong>

                            </div>

                            <div>

                                <span style="color: #a0a0a0; font-size: 0.85rem;">Fecha:</span>

                                <strong id="verFecha" style="color: #f0f0f0; display: block;">—</strong>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <h6 style="color: #d32f2f; font-weight: 700; margin-bottom: 1rem;">⚙️ Estado y Totales</h6>

                        <div style="background: rgba(211, 47, 47, 0.1); padding: 1rem; border-radius: 6px; border-left: 3px solid #d32f2f;">

                            <div style="margin-bottom: 0.5rem;">

                                <span style="color: #a0a0a0; font-size: 0.85rem;">Estado:</span>

                                <span id="verEstado" style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 20px; background: #28a745; color: white; font-weight: 600; font-size: 0.8rem;">Completada</span>

                            </div>

                            <div style="margin-bottom: 0.5rem;">

                                <span style="color: #a0a0a0; font-size: 0.85rem;">Monto (Sin IVA):</span>

                                <strong id="verMonto" style="color: #28a745; display: block; font-size: 1.1rem;">€0.00</strong>

                            </div>

                            <div>

                                <span style="color: #a0a0a0; font-size: 0.85rem;">Usuario:</span>

                                <strong id="verUsuario" style="color: #f0f0f0; display: block;">—</strong>

                            </div>

                        </div>

                    </div>

                </div>

                <hr style="border-color: rgba(211, 47, 47, 0.3); margin: 1.5rem 0;">

                <h6 style="color: #d32f2f; font-weight: 700; margin-bottom: 1rem;">📦 Productos Devueltos</h6>

                <div class="table-responsive mb-3" style="border: 1px solid rgba(211, 47, 47, 0.2); border-radius: 6px;">

                    <table class="table table-sm" style="margin: 0; background-color: rgba(20, 20, 25, 0.8);">

                        <thead style="background: rgba(211, 47, 47, 0.15);">

                            <tr>

                                <th style="color: #d32f2f; font-size: 0.8rem;">Producto</th>

                                <th style="color: #d32f2f; width: 12%; text-align: center; font-size: 0.8rem;">Cantidad</th>

                                <th style="color: #d32f2f; width: 18%; text-align: right; font-size: 0.8rem;">Precio Base</th>

                                <th style="color: #d32f2f; width: 18%; text-align: right; font-size: 0.8rem;">Subtotal</th>

                            </tr>

                        </thead>

                        <tbody id="verProductos" style="color: #f0f0f0; font-size: 0.875rem;">

                        </tbody>

                        <tfoot style="background: rgba(211, 47, 47, 0.05); border-top: 2px solid rgba(211, 47, 47, 0.3);">

                            <tr>

                                <td colspan="3" style="text-align: right; font-weight: 700; color: #d32f2f;">TOTAL (SIN IVA):</td>

                                <td style="text-align: right; font-weight: 700; color: #28a745; font-size: 1rem;" id="verTotal">€0.00</td>

                            </tr>

                        </tfoot>

                    </table>

                </div>

                <h6 style="color: #d32f2f; font-weight: 700; margin-bottom: 0.5rem;">💬 Motivo:</h6>

                <div style="background: rgba(20, 20, 25, 0.8); padding: 0.75rem; border-radius: 6px; border-left: 3px solid #d32f2f; color: #a0a0a0; font-size: 0.9rem;">

                    <span id="verMotivo">—</span>

                </div>

            </div>

            <div class="modal-footer" style="border-top: 1px solid rgba(211, 47, 47, 0.3);">

                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>

                <button type="button" class="btn btn-danger btn-sm" onclick="window.print()">🖨️ Imprimir</button>

            </div>

        </div>

    </div>

</div>

<!-- ============ SCRIPTS ============ -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

// ============ FUNCIÓN PARA ANIMAR WIDGETS ============

function animarWidget(elementId, valorNuevo) {

    const el = document.getElementById(elementId);

    if (!el) return;

    el.style.transition = 'none';

    el.style.transform = 'scale(1.2)';

    el.style.color = '#28a745';

    setTimeout(() => {

        el.innerHTML = valorNuevo;

        el.style.transition = 'all 0.5s ease';

        el.style.transform = 'scale(1)';

    }, 100);

    setTimeout(() => {

        const defaultColor = elementId === 'stat-valor' ? '#28a745' : 

                            elementId === 'stat-pendientes' ? '#ff6b6b' : '#d32f2f';

        el.style.color = defaultColor;

    }, 500);

}

document.addEventListener('DOMContentLoaded', function() {

    // ============ BÚSQUEDA DE VENTAS ============

    const inputBusqueda = document.getElementById('buscadorVenta');

    const listaDiv = document.getElementById('listaVentas');

    inputBusqueda.addEventListener('input', function() {

        const query = this.value;

        if (query.length < 1) {

            listaDiv.style.display = 'none';

            return;

        }

        fetch('/devoluciones/ventas-disponibles')

            .then(res => res.json())

            .then(data => {

                listaDiv.innerHTML = '';

                const filtrados = data.ventas.filter(v => 

                    v.numero_factura.toLowerCase().includes(query.toLowerCase()) ||

                    v.cliente.toLowerCase().includes(query.toLowerCase())

                );

                if (filtrados.length > 0) {

                    filtrados.forEach(venta => {

                        const item = document.createElement('div');

                        item.style.cssText = 'padding: 0.5rem 1rem; cursor: pointer; border-bottom: 1px solid rgba(211, 47, 47, 0.2); color: #f0f0f0; transition: background 0.2s;';

                        item.innerHTML = `<strong style="color: #d32f2f;">${venta.numero_factura}</strong> - ${venta.cliente} - <span style="color: #28a745;">€${parseFloat(venta.total).toFixed(2)}</span>`;

                        item.onmouseover = () => item.style.background = 'rgba(211, 47, 47, 0.2)';

                        item.onmouseout = () => item.style.background = 'transparent';

                        item.onclick = () => seleccionarVenta(venta);

                        listaDiv.appendChild(item);

                    });

                    listaDiv.style.display = 'block';

                }

            });

    });

    document.addEventListener('click', function(e) {

        if (e.target !== inputBusqueda) {

            listaDiv.style.display = 'none';

        }

    });

    // ============ GUARDAR FORMULARIO CON AJAX - SIN ERRORES ============

    document.getElementById('formDevolucion').addEventListener('submit', function(e) {

        e.preventDefault(); // ← STOP AQUÍ - Prevenir envío tradicional

        // Validaciones

        const ventaId = document.getElementById('ventaId').value;

        if (!ventaId) {

            alert('❌ Debes seleccionar una venta');

            return;

        }

        const motivo = document.getElementById('motivo').value.trim();

        if (!motivo) {

            alert('❌ Debes completar el motivo de devolución');

            return;

        }

        const totalDevolver = document.getElementById('totalInput').value;

        if (parseFloat(totalDevolver) <= 0) {

            alert('❌ El total a devolver debe ser mayor a €0.00');

            return;

        }

        // Recolectar productos seleccionados

        const productos = [];

        const filas = document.querySelectorAll('#tablaProd tr');

        filas.forEach(fila => {

            const checkbox = fila.querySelector('.checkbox-producto');

            const inputCantidad = fila.querySelector('.cantidad-devuelta');

            if (checkbox && checkbox.checked) {

                const cantidadDevuelta = parseInt(inputCantidad.value) || 0;

                if (cantidadDevuelta > 0) {

                    const precioBase = parseFloat(inputCantidad.dataset.precioBase);

                    const subtotal = cantidadDevuelta * precioBase;

                    const producto = {

                        producto_id: parseInt(checkbox.dataset.id),

                        cantidad: cantidadDevuelta,

                        precio_unitario: precioBase,

                        subtotal: subtotal

                    };

                    productos.push(producto);

                }

            }

        });

        if (productos.length === 0) {

            alert('❌ Debes seleccionar al menos un producto con cantidad > 0');

            return;

        }

        // Llenar campos ocultos

        document.getElementById('productosJson').value = JSON.stringify(productos);

        document.getElementById('totalInput').value = totalDevolver;

        // ✅ ENVIAR POR AJAX - NO RECARGA SIN PERMISO

        const formData = new FormData(this);

        fetch(this.action, {

            method: 'POST',

            body: formData

        })

        .then(res => res.json())

        .then(data => {

            if (data.success) {

                // ⭐ ANIMAR WIDGETS CON NUEVOS VALORES

                const stats = data.estadisticas;

                animarWidget('stat-total', stats.totalDevoluciones);

                animarWidget('stat-pendientes', stats.pendientes);

                animarWidget('stat-completadas', stats.completadas);

                animarWidget('stat-valor', '€' + parseFloat(stats.valorTotal).toFixed(2));

                // Mensaje de éxito

                setTimeout(() => {

                    alert('✅ Devolución guardada correctamente');

                    // Cerrar modal

                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalCrearDevolucion'));

                    if (modal) modal.hide();

                    // Resetear formulario

                    document.getElementById('formDevolucion').reset();

                    document.getElementById('ventaId').value = '';

                    document.getElementById('clienteVenta').value = '';

                    document.getElementById('totalVenta').value = '';

                    document.getElementById('tablaProd').innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 2rem; color: #a0a0a0;">Selecciona una venta</td></tr>';

                    // Recargar tabla después de 1.5 segundos

                    setTimeout(() => location.reload(), 500);

                }, 500);

            } else {

                alert('❌ ' + (data.message || 'Error desconocido'));

            }

        })

        .catch(err => {

            console.error('Error:', err);

            alert('❌ Error en la solicitud');

        });

    });

});

function seleccionarVenta(venta) {

    document.getElementById('ventaId').value = venta.id;

    document.getElementById('buscadorVenta').value = venta.numero_factura + ' - ' + venta.cliente;

    document.getElementById('clienteVenta').value = venta.cliente;

    document.getElementById('totalVenta').value = '€' + parseFloat(venta.total).toFixed(2);

    document.getElementById('listaVentas').style.display = 'none';

    fetch(`/ventas/${venta.id}/json`)

        .then(res => res.json())

        .then(ventaData => {

            if (ventaData && ventaData.detalles) {

                llenarProductos(ventaData.detalles);

            }

        })

        .catch(err => {

            console.error('Error cargando venta:', err);

            alert('Error al cargar los productos de la venta');

        });

}

function llenarProductos(detalles) {

    const tbody = document.getElementById('tablaProd');

    tbody.innerHTML = '';

    if (detalles && detalles.length > 0) {

        detalles.forEach((detalle) => {

            const cantidad = parseInt(detalle.cantidad) || 0;

            const precioUnitario = parseFloat(detalle.precio_unitario) || 0;

            const precioBase = precioUnitario / 1.21;

            const row = `

                <tr>

                    <td style="text-align: center;">

                        <input type="checkbox" class="form-check-input checkbox-producto" 

                            data-id="${detalle.producto_id}" 

                            onchange="actualizarFila(this)" 

                            style="cursor: pointer;">

                    </td>

                    <td style="color: #f0f0f0;">${detalle.producto?.nombre || 'Desconocido'}</td>

                    <td style="text-align: center; color: #d32f2f;">${cantidad}</td>

                    <td style="text-align: center;">

                        <input type="number" class="form-control form-control-sm cantidad-devuelta" 

                            value="0" min="0" max="${cantidad}" data-precio-base="${precioBase}" 

                            onchange="actualizarFila(this)" 

                            style="background: rgba(20, 20, 25, 0.8); border-color: rgba(211, 47, 47, 0.3); color: #f0f0f0; text-align: center; width: 60px;">

                    </td>

                    <td style="text-align: right; color: #28a745;">€${precioBase.toFixed(2)}</td>

                    <td style="text-align: right; color: #d32f2f; font-weight: 600;">€0.00</td>

                </tr>

            `;

            tbody.innerHTML += row;

        });

    }

}

function actualizarFila(element) {

    const fila = element.closest('tr');

    const checkbox = fila.querySelector('.checkbox-producto');

    const inputCantidad = fila.querySelector('.cantidad-devuelta');

    if (element === inputCantidad && inputCantidad.value > 0) {

        checkbox.checked = true;

    }

    const tbody = element.closest('tbody');

    const rows = tbody.querySelectorAll('tr');

    let totalBase = 0;

    let totalConIva = 0;

    rows.forEach((row) => {

        const checkboxFila = row.querySelector('.checkbox-producto');

        const inputCantidadFila = row.querySelector('.cantidad-devuelta');

        const subtotalCell = row.querySelector('td:last-child');

        if (checkboxFila && checkboxFila.checked && inputCantidadFila) {

            const cantidadDevuelta = parseInt(inputCantidadFila.value) || 0;

            const precioBase = parseFloat(inputCantidadFila.dataset.precioBase) || 0;

            const subtotalBase = cantidadDevuelta * precioBase;

            const subtotalConIva = subtotalBase * 1.21;

            subtotalCell.textContent = '€' + subtotalBase.toFixed(2);

            totalBase += subtotalBase;

            totalConIva += subtotalConIva;

        } else {

            subtotalCell.textContent = '€0.00';

            if (inputCantidadFila) inputCantidadFila.value = 0;

        }

    });

    const iva = totalConIva - totalBase;

    document.getElementById('totalConIva').textContent = '€' + totalConIva.toFixed(2);

    document.getElementById('montoIva').textContent = '€' + iva.toFixed(2);

    document.getElementById('totalDevolver').textContent = '€' + totalBase.toFixed(2);

    document.getElementById('totalInput').value = totalBase.toFixed(2);

}

function verDevolucion(id) {

    fetch(`/devoluciones/${id}/json`)

        .then(res => res.json())

        .then(data => {

            const modal = new bootstrap.Modal(document.getElementById('modalVerDevolucion'));

            document.getElementById('verCodigo').textContent = 'DEV-' + String(data.id).padStart(4, '0');

            document.getElementById('verCliente').textContent = data.venta?.cliente || '—';

            document.getElementById('verFecha').textContent = data.fecha ? new Date(data.fecha).toLocaleDateString('es-ES') : '—';

            document.getElementById('verMonto').textContent = '€' + (parseFloat(data.total_devuelto) || 0).toFixed(2);

            document.getElementById('verUsuario').textContent = data.usuario?.name || '—';

            const estadoSpan = document.getElementById('verEstado');

            const estadoTexto = data.estado ? data.estado.charAt(0).toUpperCase() + data.estado.slice(1) : 'Pendiente';

            estadoSpan.textContent = estadoTexto;

            estadoSpan.style.background = data.estado === 'completada' ? '#28a745' : '#d32f2f';

            const tbody = document.getElementById('verProductos');

            tbody.innerHTML = '';

            let total = 0;

            if (data.detalles && data.detalles.length > 0) {

                data.detalles.forEach(det => {

                    const cantidad = parseInt(det.cantidad) || 0;

                    const precio = parseFloat(det.precio_unitario) || 0;

                    const subtotal = cantidad * precio;

                    total += subtotal;

                    const row = `

                        <tr>

                            <td style="color: #f0f0f0;">${det.producto?.nombre || '—'}</td>

                            <td style="text-align: center; color: #d32f2f;">${cantidad}</td>

                            <td style="text-align: right; color: #28a745;">€${precio.toFixed(2)}</td>

                            <td style="text-align: right; color: #d32f2f;">€${subtotal.toFixed(2)}</td>

                        </tr>

                    `;

                    tbody.innerHTML += row;

                });

            }

            document.getElementById('verTotal').textContent = '€' + total.toFixed(2);

            document.getElementById('verMotivo').textContent = data.motivo || '—';

            modal.show();

        })

        .catch(err => {

            console.error('Error:', err);

            alert('Error al cargar la devolución');

        });

}

function cambiarEstado(id, estado) {

    if (confirm('¿Completar esta devolución?')) {

        fetch(`/devoluciones/${id}/estado`, {

            method: 'PATCH',

            headers: {

                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,

                'Content-Type': 'application/json'

            },

            body: JSON.stringify({ estado: estado })

        })

        .then(res => res.json())

        .then(data => {

            if (data.success) {

                // ⭐ ANIMAR WIDGETS CON NUEVOS VALORES

                const stats = data.estadisticas;

                animarWidget('stat-total', stats.totalDevoluciones);

                animarWidget('stat-pendientes', stats.pendientes);

                animarWidget('stat-completadas', stats.completadas);

                animarWidget('stat-valor', '€' + parseFloat(stats.valorTotal).toFixed(2));

                alert('✅ ' + data.message);

                setTimeout(() => location.reload(), 1000);

            } else {

                alert('❌ Error: ' + (data.message || 'No especificado'));

            }

        })

        .catch(err => {

            console.error('Error:', err);

            alert('Error al cambiar estado');

        });

    }

}

</script>

@endsection
