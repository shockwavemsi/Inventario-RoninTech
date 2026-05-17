@extends('layouts.app')

@section('title', 'Sistema de Ventas')

@section('content')

<!-- HEADER PERSONALIZADO -->
<div style="background: linear-gradient(to right, #0d0d0e 0%, #111111 100%); border-bottom: 2px solid #e63946; padding: 1.5rem 0; margin: -30px -30px 30px -30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 30px; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 50px; height: 50px; background-color: #e63946; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 28px; color: white;">💰</div>
            <div>
                <h1 style="font-size: 1.8rem; font-weight: bold; color: #f0f0f0; margin: 0;">SISTEMA DE VENTAS</h1>
                <p style="font-size: 0.75rem; color: #a0a0a0; margin: 0;">RoninTech - Gestión Integral de Ventas</p>
            </div>
        </div>
    </div>
</div>

<!-- FLUJO SECTION -->
<div style="background-color: rgba(20, 20, 25, 0.65); border-bottom: 2px solid #e63946; padding: 2rem 0; margin: -30px -30px 30px -30px; backdrop-filter: blur(20px);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 30px;">
        <p style="font-size: 0.875rem; color: #a0a0a0; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">Flujo de Ventas</p>

        <div style="display: flex; justify-content: center; align-items: center; gap: 2rem; flex-wrap: wrap;">
            <!-- VENTA -->
            <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem; cursor: pointer;" onclick="document.getElementById('nav-ventas').click()">
                <div style="width: 60px; height: 60px; background: rgba(230, 57, 70, 0.2); border: 2px solid #e63946; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; color: #e63946; font-weight: 700; transition: all 0.3s;">
                    1️⃣
                </div>
                <span style="font-size: 0.85rem; color: #f0f0f0; font-weight: 600;">VENTA</span>
            </div>

            <!-- FLECHA -->
            <div style="font-size: 2rem; color: #e63946; margin-top: 1.5rem;">→</div>

            <!-- FACTURA VENTA -->
            <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem; cursor: pointer;" onclick="document.getElementById('nav-facturas').click()">
                <div style="width: 60px; height: 60px; background: rgba(230, 57, 70, 0.2); border: 2px solid #e63946; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; color: #e63946; font-weight: 700;">
                    2️⃣
                </div>
                <span style="font-size: 0.85rem; color: #f0f0f0; font-weight: 600;">FACTURA</span>
            </div>
        </div>
    </div>
</div>

<!-- MAIN CONTENT -->
<div style="max-width: 1200px; margin: 0 auto;">

    <!-- ============ PESTAÑAS / TABS ============ -->
    <ul class="nav nav-tabs" style="border-bottom: 2px solid #e63946; margin-bottom: 2rem; background: rgba(20, 20, 25, 0.5); border-radius: 8px 8px 0 0; padding: 0.5rem;">
        <li class="nav-item">
            <button type="button" id="nav-ventas" class="nav-link active" data-bs-toggle="tab" data-bs-target="#ventas-tab" style="color: #e63946; font-weight: 700; border: none;">
                📋 VENTAS
            </button>
        </li>
        <li class="nav-item">
            <button type="button" id="nav-facturas" class="nav-link" data-bs-toggle="tab" data-bs-target="#facturas-tab" style="color: #a0a0a0; font-weight: 700; border: none;">
                🧾 FACTURAS VENTA
            </button>
        </li>
    </ul>

    <!-- ============ CONTENIDO TABS ============ -->
    <div class="tab-content">

        <!-- TAB: VENTAS -->
        <div class="tab-pane fade show active" id="ventas-tab">
            <div style="background: rgba(20, 20, 25, 0.65); border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 8px; padding: 1.5rem; backdrop-filter: blur(20px); margin-bottom: 2rem;">

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h5 style="color: #e63946; font-weight: 700; margin: 0;">📦 Ventas Registradas</h5>
                    <button type="button" class="btn btn-danger btn-sm" onclick="window.CrearVentaModal.mostrar()">
                        ➕ Nueva Venta
                    </button>
                </div>

                <div style="overflow-x: auto; border-radius: 8px;">
                    <table class="table table-dark table-hover" style="margin: 0;">
                        <thead style="background: rgba(230, 57, 70, 0.15);">
                            <tr>
                                <th style="color: #e63946; font-weight: 700;">Nº Venta</th>
                                <th style="color: #e63946; font-weight: 700;">Cliente</th>
                                <th style="color: #e63946; font-weight: 700;">Fecha</th>
                                <th style="color: #e63946; font-weight: 700;">Método Pago</th>
                                <th style="color: #e63946; font-weight: 700;">Total</th>
                                <th style="color: #e63946; font-weight: 700;">Estado</th>
                                <th style="color: #e63946; font-weight: 700;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablasVentas">
                            @forelse($ventas as $venta)
                                <tr>
                                    <td style="color: #e63946; font-weight: 600;">{{ $venta['numero_factura'] }}</td>
                                    <td style="color: #f0f0f0;">{{ $venta['cliente'] }}</td>
                                    <td style="color: #a0a0a0;">{{ $venta['fecha_venta'] }}</td>
                                    <td style="color: #f0f0f0;">{{ $venta['metodo_pago'] }}</td>
                                    <td style="color: #90ee90; font-weight: 600;">€{{ number_format($venta['total'], 2, ',', '.') }}</td>
                                    <td>
                                        <span class="badge" style="background: {{ $venta['estado'] === 'completada' ? '#90ee90' : ($venta['estado'] === 'pendiente' ? '#ffc107' : '#dc3545') }}; color: black; font-size: 0.75rem;">
                                            {{ ucfirst($venta['estado']) }}
                                        </span>
                                    </td>
                                    <td style="font-size: 0.85rem; gap: 0.25rem; display: flex;">
<!-- ✅ CORRECTO - llamar a ModalVerVenta.mostrar() -->
<button type="button" class="btn btn-sm btn-warning" onclick="window.ModalVerVenta.mostrar({{ $venta['id'] }})" style="padding: 0.25rem 0.5rem;">👁️</button>
<button type="button" class="btn btn-sm btn-info" onclick="generarPDFVenta({{ $venta['id'] }})" style="padding: 0.25rem 0.5rem;">📥</button>
                                        <form action="{{ route('ventas.destroy', $venta['id']) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar venta?')" style="padding: 0.25rem 0.5rem;">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 2rem; color: #a0a0a0;">📭 Sin ventas registradas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB: FACTURAS VENTA -->
        <div class="tab-pane fade" id="facturas-tab">
            <div style="background: rgba(20, 20, 25, 0.65); border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 8px; padding: 1.5rem; backdrop-filter: blur(20px); margin-bottom: 2rem;">

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h5 style="color: #e63946; font-weight: 700; margin: 0;">🧾 Facturas de Venta</h5>
                    <button type="button" class="btn btn-danger btn-sm" onclick="window.CrearFacturaVentaModal.mostrar()">
                        ➕ Nueva Factura
                    </button>
                </div>

                <div style="overflow-x: auto; border-radius: 8px;">
                    <table class="table table-dark table-hover" style="margin: 0;">
                        <thead style="background: rgba(230, 57, 70, 0.15);">
                            <tr>
                                <th style="color: #e63946; font-weight: 700;">Nº Factura</th>
                                <th style="color: #e63946; font-weight: 700;">Venta</th>
                                <th style="color: #e63946; font-weight: 700;">Cliente</th>
                                <th style="color: #e63946; font-weight: 700;">Fecha</th>
                                <th style="color: #e63946; font-weight: 700;">Total</th>
                                <th style="color: #e63946; font-weight: 700;">Estado</th>
                                <th style="color: #e63946; font-weight: 700;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablasFacturas">
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem; color: #a0a0a0;">📭 Sin facturas registradas</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

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
                            <span style="color: #a0a0a0;">IVA:</span>
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                <input type="number" name="iva_porcentaje" id="ivaPorcentaje" value="21" min="0" max="100" step="0.01" 
                                    onchange="window.CrearVentaModal.calcularTotales()"
                                    style="width: 50px; padding: 0.25rem; background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; text-align: right; font-size: 0.85rem;">
                                <span>%</span>
                                <strong id="ivaDisplay" style="color: #f0f0f0; min-width: 60px; text-align: right;">0.00€</strong>
                            </div>
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

<!-- ============ MODAL CREAR FACTURA VENTA ============ -->
<div class="modal fade" id="modalFacturaVenta" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3);">
            <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946;">
                <h5 class="modal-title" style="color: #e63946; font-weight: 700;">🧾 Nueva Factura de Venta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="color: #f0f0f0; padding: 2rem; text-align: center;">
                <p style="color: #a0a0a0; font-size: 1.1rem;">⏳ Funcionalidad en desarrollo...</p>
                <p style="color: #a0a0a0; font-size: 0.9rem;">Las facturas de venta se crearán desde las ventas existentes.</p>
            </div>
        </div>
    </div>
</div>

<!-- ✅ FUNCIÓN HELPER PARA FORMATEAR FECHAS -->
<script>
    /**
     * Formatear fecha ISO a formato DD/MM/YYYY
     */
    window.formatearFecha = function(fechaISO) {
        if (!fechaISO) return '—';

        const fecha = new Date(fechaISO);
        const dia = String(fecha.getDate()).padStart(2, '0');
        const mes = String(fecha.getMonth() + 1).padStart(2, '0');
        const año = fecha.getFullYear();

        return `${dia}/${mes}/${año}`;
    };
</script>

<!-- ============ MODAL VER VENTA ============ -->
<div class="modal fade" id="modalVerVenta" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3);">

            <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946;">
                <h5 class="modal-title" style="color: #e63946; font-weight: 700;">📋 Detalles de la Venta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="color: #f0f0f0; padding: 1.5rem; max-height: 80vh; overflow-y: auto;">

                <!-- INFORMACIÓN GENERAL -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 style="color: #e63946; font-weight: 700; margin-bottom: 1rem;">📌 Información General</h6>
                        <div style="background: rgba(230, 57, 70, 0.1); padding: 1rem; border-radius: 6px; border-left: 3px solid #e63946;">
                            <div style="margin-bottom: 0.5rem;">
                                <span style="color: #a0a0a0; font-size: 0.85rem;">Código:</span>
                                <strong id="detalleNumero" style="color: #e63946; display: block;">V-001</strong>
                            </div>
                            <div style="margin-bottom: 0.5rem;">
                                <span style="color: #a0a0a0; font-size: 0.85rem;">Cliente:</span>
                                <strong id="detalleCliente" style="color: #f0f0f0; display: block;">Juan Pérez</strong>
                            </div>
                            <div style="margin-bottom: 0.5rem;">
                                <span style="color: #a0a0a0; font-size: 0.85rem;">Documento:</span>
                                <strong id="detalleDocumento" style="color: #f0f0f0; display: block;">12345678A</strong>
                            </div>
                            <div>
                                <span style="color: #a0a0a0; font-size: 0.85rem;">Fecha Venta:</span>
                                <strong id="detalleFecha" style="color: #f0f0f0; display: block;">15/3/2025</strong>
                            </div>
                        </div>
                    </div>

                    <!-- ESTADO Y DETALLES -->
                    <div class="col-md-6">
                        <h6 style="color: #e63946; font-weight: 700; margin-bottom: 1rem;">⚙️ Estado y Detalles</h6>
                        <div style="background: rgba(230, 57, 70, 0.1); padding: 1rem; border-radius: 6px; border-left: 3px solid #e63946;">
                            <div style="margin-bottom: 0.5rem;">
                                <span style="color: #a0a0a0; font-size: 0.85rem;">Estado:</span>
                                <span id="detalleEstado" style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 20px; background: #90ee90; color: black; font-weight: 600; font-size: 0.8rem;">Completada</span>
                            </div>
                            <div style="margin-bottom: 0.5rem;">
                                <span style="color: #a0a0a0; font-size: 0.85rem;">Método de Pago:</span>
                                <strong id="detalleMetodo" style="color: #f0f0f0; display: block;">Tarjeta</strong>
                            </div>
                            <div>
                                <span style="color: #a0a0a0; font-size: 0.85rem;">Usuario:</span>
                                <strong id="detalleUsuario" style="color: #f0f0f0; display: block;">User</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1.5rem 0;">

                <!-- PRODUCTOS VENDIDOS -->
                <h6 style="color: #e63946; font-weight: 700; margin-bottom: 1rem;">📦 Productos Vendidos</h6>
                <div class="table-responsive mb-3" style="border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 6px;">
                    <table class="table table-sm" id="tablaProductosDetalle" style="margin: 0;">
                        <thead style="background: rgba(230, 57, 70, 0.15);">
                            <tr>
                                <th style="color: #e63946; font-size: 0.8rem;">Producto</th>
                                <th style="color: #e63946; width: 10%; text-align: center; font-size: 0.8rem;">Cantidad</th>
                                <th style="color: #e63946; width: 15%; text-align: right; font-size: 0.8rem;">Precio Unit.</th>
                                <th style="color: #e63946; width: 15%; text-align: right; font-size: 0.8rem;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="detalleProductos" style="color: #f0f0f0; font-size: 0.875rem;">
                            <!-- Se rellena con JS -->
                        </tbody>
                    </table>
                </div>

                <!-- TOTALES -->
                <div style="background: rgba(230, 57, 70, 0.1); padding: 1rem; border-radius: 6px; border-left: 3px solid #e63946; margin-bottom: 1rem;">
                    <div class="d-flex justify-content-between" style="margin-bottom: 0.5rem; font-size: 0.9rem;">
                        <span style="color: #a0a0a0;">SUBTOTAL:</span>
                        <strong id="detalleSub" style="color: #f0f0f0;">€0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between" style="margin-bottom: 0.5rem; font-size: 0.9rem;">
                        <span style="color: #a0a0a0;">IMPUESTO (21%):</span>
                        <strong id="detalleIva" style="color: #f0f0f0;">€0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between" style="font-size: 1.1rem; padding-top: 0.75rem; border-top: 1px solid rgba(230, 57, 70, 0.3);">
                        <span style="color: #e63946; font-weight: 700;">TOTAL:</span>
                        <strong id="detalleTotal" style="color: #e63946; font-size: 1.3rem;">€0.00</strong>
                    </div>
                </div>

                <!-- OBSERVACIONES -->
                <h6 style="color: #e63946; font-weight: 700; margin-bottom: 0.5rem;">💬 Observaciones:</h6>
                <div style="background: rgba(20, 20, 25, 0.8); padding: 0.75rem; border-radius: 6px; border-left: 3px solid #e63946; color: #a0a0a0; font-size: 0.9rem; min-height: 50px;">
                    <span id="detalleObservaciones">—</span>
                </div>

            </div>

        </div>
    </div>
</div>

<!-- ✅ CARGAR MODAL VER VENTA (SIN MÓDULOS - ANTES QUE NADA) -->
<script src="{{ asset('js/ventas/modales/modal-ver-venta.js') }}"></script>


</div>

<!-- ✅ FUNCIÓN PARA GENERAR PDF (DEBE ESTAR AQUÍ) -->
<script>
    /**
     * Generar PDF de venta
     */
    async function generarPDFVenta(ventaId) {
        try {
            // Mostrar loading
            const btn = event.target;
            btn.disabled = true;

            // Enviar al backend
            const response = await fetch('/generar-pdf-venta', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ venta_id: ventaId })
            });

            if (response.ok) {
                // Descargar PDF
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = `Factura_V-${ventaId.toString().padStart(3, '0')}.pdf`;
                link.click();
                window.URL.revokeObjectURL(url);

                // Restaurar botón
                btn.disabled = false;
                btn.innerHTML = '📥 PDF';
            } else {
                const error = await response.json();
                alert('Error: ' + error.error);
                btn.disabled = false;
                btn.innerHTML = '📥 PDF';
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al generar el PDF');
            btn.disabled = false;
            btn.innerHTML = '📥 PDF';
        }
    }
</script>

@push('scripts')
    <script>
        // Verificar que se cargó correctamente
        console.log('🔍 Verificando ModalVerVenta...');
        if (window.ModalVerVenta) {
            console.log('✅ ModalVerVenta cargado correctamente');
            window.ModalVerVenta.init();
        } else {
            console.error('❌ ModalVerVenta NO está disponible');
        }
    </script>

    <!-- ✅ CARGAR MÓDULOS DE VENTAS DESPUÉS -->
    <script type="module">
        console.log('🚀 Cargando módulos de ventas...');
        import VentaManager from '{{ asset("js/ventas/venta.js") }}';
    </script>
@endpush

@endsection