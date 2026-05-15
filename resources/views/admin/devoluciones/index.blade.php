@extends('layouts.app')

@section('title', 'Devoluciones de Ventas')

@section('content')

<!-- ✅ CARGAR MODAL VER DEVOLUCION PRIMERO -->
<script src="{{ asset('js/devoluciones/modales/modal-ver-devolucion.js') }}"></script>

<!-- HEADER PERSONALIZADO -->
<div style="background: linear-gradient(to right, #0d0d0e 0%, #111111 100%); border-bottom: 2px solid #e63946; padding: 1.5rem 0; margin: -30px -30px 30px -30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 30px; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 50px; height: 50px; background-color: #e63946; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 28px; color: white;">↩️</div>
            <div>
                <h1 style="font-size: 1.8rem; font-weight: bold; color: #f0f0f0; margin: 0;">DEVOLUCIONES DE VENTAS</h1>
                <p style="font-size: 0.75rem; color: #a0a0a0; margin: 0;">RoninTech - Gestión de Devoluciones</p>
            </div>
        </div>
    </div>
</div>

<!-- ESTADÍSTICAS -->
<div class="row mb-4" style="max-width: 1200px; margin: 2rem auto 0;">
    <div class="col-md-3">
        <div style="background: rgba(20, 20, 25, 0.65); border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 8px; padding: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="font-size: 2rem;">📦</div>
                <div>
                    <span style="color: #a0a0a0; font-size: 0.85rem;">Total Devoluciones</span>
                    <strong style="color: #e63946; font-size: 1.5rem; display: block;">{{ $totalDevoluciones }}</strong>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div style="background: rgba(20, 20, 25, 0.65); border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 8px; padding: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="font-size: 2rem;">⏳</div>
                <div>
                    <span style="color: #a0a0a0; font-size: 0.85rem;">Pendientes</span>
                    <strong style="color: #ffc107; font-size: 1.5rem; display: block;">{{ $pendientes }}</strong>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div style="background: rgba(20, 20, 25, 0.65); border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 8px; padding: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="font-size: 2rem;">✅</div>
                <div>
                    <span style="color: #a0a0a0; font-size: 0.85rem;">Completadas</span>
                    <strong style="color: #90ee90; font-size: 1.5rem; display: block;">{{ $completadas }}</strong>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div style="background: rgba(20, 20, 25, 0.65); border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 8px; padding: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="font-size: 2rem;">💰</div>
                <div>
                    <span style="color: #a0a0a0; font-size: 0.85rem;">Valor Total</span>
                    <strong style="color: #e63946; font-size: 1.5rem; display: block;">€{{ number_format($valorTotal, 2, ',', '.') }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CONTENIDO PRINCIPAL -->
<div style="max-width: 1200px; margin: 2rem auto; padding: 0 30px;">
    <div style="background: rgba(20, 20, 25, 0.65); border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 8px; padding: 1.5rem; backdrop-filter: blur(20px);">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h5 style="color: #e63946; font-weight: 700; margin: 0;">📋 Devoluciones Registradas</h5>
            <button type="button" class="btn btn-danger btn-sm" onclick="window.ModalCrearDevolucion.mostrar()">
                ➕ Nueva Devolución
            </button>
        </div>

        <div style="overflow-x: auto; border-radius: 8px;">
            <table class="table table-dark table-hover" style="margin: 0;">
                <thead style="background: rgba(230, 57, 70, 0.15);">
                    <tr>
                        <th style="color: #e63946; font-weight: 700;">Nº Devolución</th>
                        <th style="color: #e63946; font-weight: 700;">Cliente</th>
                        <th style="color: #e63946; font-weight: 700;">Fecha</th>
                        <th style="color: #e63946; font-weight: 700;">Producto</th>
                        <th style="color: #e63946; font-weight: 700;">Monto</th>
                        <th style="color: #e63946; font-weight: 700;">Estado</th>
                        <th style="color: #e63946; font-weight: 700;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($devoluciones as $dev)
                        <tr>
                            <td style="color: #e63946; font-weight: 600;">{{ $dev['numero'] }}</td>
                            <td style="color: #f0f0f0;">{{ $dev['cliente'] }}</td>
                            <td style="color: #a0a0a0;">{{ $dev['fecha'] }}</td>
                            <td style="color: #f0f0f0;">{{ $dev['producto'] }}</td>
                            <td style="color: #90ee90; font-weight: 600;">€{{ number_format($dev['total'], 2, ',', '.') }}</td>
                            <td>
                                <span class="badge" style="background: {{ $dev['estado'] === 'completada' ? '#90ee90' : '#ffc107' }}; color: {{ $dev['estado'] === 'completada' ? 'black' : 'black' }}; font-size: 0.75rem;">
                                    {{ ucfirst($dev['estado']) }}
                                </span>
                            </td>
                            <td style="font-size: 0.85rem; gap: 0.25rem; display: flex;">
                                <button type="button" class="btn btn-sm btn-warning" onclick="window.ModalVerDevolucion.mostrar({{ $dev['id'] }})" style="padding: 0.25rem 0.5rem;">👁️</button>
                                @if($dev['estado'] === 'pendiente')
                                    <button type="button" class="btn btn-sm btn-success" onclick="cambiarEstado({{ $dev['id'] }}, 'completada')" style="padding: 0.25rem 0.5rem;">✓</button>
                                @endif
                                <form action="{{ route('devoluciones.destroy', $dev['id']) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar devolución?')" style="padding: 0.25rem 0.5rem;">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem; color: #a0a0a0;">📭 Sin devoluciones registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- ============ MODAL CREAR DEVOLUCIÓN ============ -->
<div class="modal fade" id="modalCrearDevolucion" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3);">

            <form id="formDevolucion" method="POST" action="{{ route('devoluciones.store') }}">
                @csrf

                <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946;">
                    <h5 class="modal-title" style="color: #e63946; font-weight: 700;">➕ Nueva Devolución de Venta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="color: #f0f0f0; padding: 1.5rem; max-height: 80vh; overflow-y: auto;">

                    <!-- SELECCIONAR VENTA -->
                    <h6 style="color: #e63946; font-weight: 700; margin-bottom: 1rem;">📅 Seleccionar Venta</h6>
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Venta *</label>
                            <div style="position: relative;">
                                <input type="text" id="crearDevBuscadorVenta" class="form-control form-control-sm" placeholder="Busca por número, cliente o monto..." autocomplete="off" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;">
                                <ul id="crearDevListaVentas" class="list-group" style="max-height: 200px; overflow-y: auto; display: none; position: absolute; width: 100%; z-index: 1000; background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3); border-radius: 6px; margin-top: 0.25rem; top: 100%; left: 0;"></ul>
                            </div>
                            <input type="hidden" name="venta_id" id="crearDevVentaId">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Cliente</label>
                            <input type="text" id="crearDevCliente" class="form-control form-control-sm" readonly style="background: rgba(100, 100, 100, 0.2); font-size: 0.9rem; color: #a0a0a0;">
                        </div>
                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1rem 0;">

                    <!-- PRODUCTOS VENDIDOS -->
                    <h6 style="color: #e63946; font-weight: 700; margin-bottom: 1rem;">📦 Productos Vendidos</h6>
                    <div class="table-responsive mb-2" style="border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 6px;">
                        <table class="table table-sm" style="margin: 0;">
                            <thead style="background: rgba(230, 57, 70, 0.15);">
                                <tr>
                                    <th style="color: #e63946; width: 8%; text-align: center; font-size: 0.8rem;">✓</th>
                                    <th style="color: #e63946; font-size: 0.8rem;">Producto</th>
                                    <th style="color: #e63946; width: 10%; text-align: center; font-size: 0.8rem;">Cant.Orig</th>
                                    <th style="color: #e63946; width: 12%; text-align: center; font-size: 0.8rem;">Cant.Dev</th>
                                    <th style="color: #e63946; width: 12%; text-align: right; font-size: 0.8rem;">Precio</th>
                                    <th style="color: #e63946; width: 15%; text-align: right; font-size: 0.8rem;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="crearDevProductos" style="color: #f0f0f0; font-size: 0.875rem;">
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 2rem; color: #a0a0a0;">
                                        Selecciona una venta
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot style="background: rgba(230, 57, 70, 0.1);">
                                <tr>
                                    <td colspan="5" style="text-align: right; font-weight: 700; color: #e63946;">TOTAL:</td>
                                    <td style="text-align: right; font-weight: 700; color: #e63946; font-size: 1rem;" id="crearDevTotalFooter">€0.00</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1rem 0;">

                    <!-- DETALLES DE DEVOLUCIÓN -->
                    <h6 style="color: #e63946; font-weight: 700; margin-bottom: 1rem;">📄 Detalles de Devolución</h6>

                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 0.85rem;">Motivo *</label>
                        <textarea name="motivo" id="crearDevMotivo" class="form-control form-control-sm" rows="3" placeholder="Describe el motivo..." required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 0.85rem;">Estado *</label>
                        <select name="estado" id="crearDevEstado" class="form-select form-select-sm" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;">
                            <option value="pendiente">Pendiente</option>
                            <option value="completada" selected>Completada</option>
                        </select>
                    </div>

                    <div style="background: rgba(230, 57, 70, 0.1); padding: 1rem; border-radius: 6px; border-left: 3px solid #e63946;">
                        <div class="d-flex justify-content-between" style="font-size: 0.9rem; margin-bottom: 0.5rem;">
                            <span style="color: #a0a0a0;">SUBTOTAL:</span>
                            <strong id="crearDevSubtotal" style="color: #f0f0f0;">€0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between" style="font-size: 0.9rem; margin-bottom: 0.5rem;">
                            <span style="color: #a0a0a0;">IVA (21%):</span>
                            <strong id="crearDevIva" style="color: #f0f0f0;">€0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between" style="font-size: 1.1rem; padding-top: 0.75rem; border-top: 1px solid rgba(230, 57, 70, 0.3);">
                            <span style="color: #e63946; font-weight: 700;">TOTAL DEVOLUCIÓN:</span>
                            <strong id="crearDevTotalModal" style="color: #e63946; font-size: 1.3rem;">€0.00</strong>
                        </div>
                    </div>

                    <input type="hidden" name="productos_json" id="crearDevProductosJson" value="[]">
                    <input type="hidden" name="total_devuelto" id="crearDevTotalInput" value="0">

                </div>

                <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3);">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger btn-sm">✓ Guardar Devolución</button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- ============ MODAL VER DEVOLUCIÓN ============ -->
<div class="modal fade" id="modalVerDevolucion" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3);">

            <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946;">
                <h5 class="modal-title" style="color: #e63946; font-weight: 700;">📋 Detalles de la Devolución</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="color: #f0f0f0; padding: 1.5rem; max-height: 80vh; overflow-y: auto;">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 style="color: #e63946; font-weight: 700; margin-bottom: 1rem;">📌 Información General</h6>
                        <div style="background: rgba(230, 57, 70, 0.1); padding: 1rem; border-radius: 6px; border-left: 3px solid #e63946;">
                            <div style="margin-bottom: 0.5rem;">
                                <span style="color: #a0a0a0; font-size: 0.85rem;">Código:</span>
                                <strong id="verDevCodigo" style="color: #e63946; display: block;">DEV-0001</strong>
                            </div>
                            <div style="margin-bottom: 0.5rem;">
                                <span style="color: #a0a0a0; font-size: 0.85rem;">Cliente:</span>
                                <strong id="verDevCliente" style="color: #f0f0f0; display: block;">—</strong>
                            </div>
                            <div>
                                <span style="color: #a0a0a0; font-size: 0.85rem;">Fecha:</span>
                                <strong id="verDevFecha" style="color: #f0f0f0; display: block;">—</strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 style="color: #e63946; font-weight: 700; margin-bottom: 1rem;">⚙️ Estado y Totales</h6>
                        <div style="background: rgba(230, 57, 70, 0.1); padding: 1rem; border-radius: 6px; border-left: 3px solid #e63946;">
                            <div style="margin-bottom: 0.5rem;">
                                <span style="color: #a0a0a0; font-size: 0.85rem;">Estado:</span>
                                <span id="verDevEstado" style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 20px; background: #90ee90; color: black; font-weight: 600; font-size: 0.8rem;">Completada</span>
                            </div>
                            <div style="margin-bottom: 0.5rem;">
                                <span style="color: #a0a0a0; font-size: 0.85rem;">Monto Devuelto:</span>
                                <strong id="verDevMonto" style="color: #e63946; display: block; font-size: 1.1rem;">€0.00</strong>
                            </div>
                            <div>
                                <span style="color: #a0a0a0; font-size: 0.85rem;">Usuario:</span>
                                <strong id="verDevUsuario" style="color: #f0f0f0; display: block;">—</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1.5rem 0;">

                <h6 style="color: #e63946; font-weight: 700; margin-bottom: 1rem;">📦 Productos Devueltos</h6>
                <div class="table-responsive mb-3" style="border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 6px;">
                    <table class="table table-sm" style="margin: 0;">
                        <thead style="background: rgba(230, 57, 70, 0.15);">
                            <tr>
                                <th style="color: #e63946; font-size: 0.8rem;">Producto</th>
                                <th style="color: #e63946; width: 12%; text-align: center; font-size: 0.8rem;">Cantidad</th>
                                <th style="color: #e63946; width: 18%; text-align: right; font-size: 0.8rem;">Precio Unit.</th>
                                <th style="color: #e63946; width: 18%; text-align: right; font-size: 0.8rem;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="verDevProductos" style="color: #f0f0f0; font-size: 0.875rem;">
                        </tbody>
                        <tfoot style="background: rgba(230, 57, 70, 0.05); border-top: 2px solid rgba(230, 57, 70, 0.3);">
                            <tr>
                                <td colspan="3" style="text-align: right; font-weight: 700; color: #e63946;">TOTAL:</td>
                                <td style="text-align: right; font-weight: 700; color: #e63946; font-size: 1rem;" id="verDevTotal">€0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <h6 style="color: #e63946; font-weight: 700; margin-bottom: 0.5rem;">💬 Motivo:</h6>
                <div style="background: rgba(20, 20, 25, 0.8); padding: 0.75rem; border-radius: 6px; border-left: 3px solid #e63946; color: #a0a0a0; font-size: 0.9rem;">
                    <span id="verDevMotivo">—</span>
                </div>

            </div>

            <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3);">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">❌ Cerrar</button>
                <button type="button" class="btn btn-danger btn-sm" onclick="window.print()">🖨️ Imprimir</button>
            </div>

        </div>
    </div>
</div>


<!-- ============ MODAL CREAR DEVOLUCIÓN ============ -->
<script src="{{ asset('js/devoluciones/modales/modal-crear-devolucion.js') }}"></script>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.ModalVerDevolucion) {
                window.ModalVerDevolucion.init();
            }
            if (window.ModalCrearDevolucion) {
                window.ModalCrearDevolucion.init();
            }
        });

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
                        alert('✅ ' + data.message);
                        window.location.reload();
                    }
                });
            }
        }
    </script>
@endpush

@endsection