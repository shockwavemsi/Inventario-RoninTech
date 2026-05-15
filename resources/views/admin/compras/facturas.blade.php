@extends('layouts.app')

@section('title', 'Facturas de Compra')

@section('content')

<!-- HEADER PERSONALIZADO -->
<div style="background: linear-gradient(to right, #0d0d0e 0%, #111111 100%); border-bottom: 2px solid #e63946; padding: 1.5rem 0; margin: -30px -30px 30px -30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 30px; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 50px; height: 50px; background-color: #e63946; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 28px; color: white;">💳</div>
            <div>
                <h1 style="font-size: 1.8rem; font-weight: bold; color: #f0f0f0; margin: 0;">FACTURAS DE COMPRA</h1>
                <p style="font-size: 0.75rem; color: #a0a0a0; margin: 0;">Gestión de Facturación</p>
            </div>
        </div>
    </div>
</div>

<!-- BARRA DE ACCIONES -->
<div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; margin-top: 1.5rem;">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalFactura">
        ➕ Crear Nueva Factura
    </button>
    <input type="text" id="buscador" class="form-control" placeholder="Buscar por número de factura..." style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; max-width: 400px;">
</div>

<!-- TABLA DE FACTURAS -->
<div style="max-width: 1200px; margin: 0 auto;">
    <div class="table-responsive" style="border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 6px;">
        <table class="table table-sm" style="margin: 0; background: rgba(20, 20, 25, 0.8);">
            <thead style="background: rgba(230, 57, 70, 0.15);">
                <tr>
                    <th style="color: #e63946; font-weight: 700;">Nº Factura</th>
                    <th style="color: #e63946; font-weight: 700;">Albarán</th>
                    <th style="color: #e63946; font-weight: 700;">Proveedor</th>
                    <th style="color: #e63946; font-weight: 700;">Fecha Factura</th>
                    <th style="color: #e63946; font-weight: 700;">Fecha Vencimiento</th>
                    <th style="color: #e63946; font-weight: 700;">Estado</th>
                    <th style="color: #e63946; font-weight: 700;">Total</th>
                    <th style="color: #e63946; font-weight: 700;">Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-facturas">
                @forelse($facturas as $factura)
                    <tr data-factura-id="{{ $factura->id }}" data-numero="{{ $factura->numero_factura }}" style="border-bottom: 1px solid rgba(230, 57, 70, 0.1);">
                        <td style="color: #e63946; font-weight: 600;">{{ $factura->numero_factura }}</td>
                        <td style="color: #f0f0f0;">{{ $factura->albaranCompra->numero_albaran ?? '—' }}</td>
                        <td style="color: #f0f0f0;">{{ $factura->proveedor->nombre ?? '—' }}</td>
                        <td style="color: #a0a0a0;">{{ $factura->fecha_factura->format('d/m/Y') }}</td>
                        <td style="color: #a0a0a0;">{{ $factura->fecha_vencimiento->format('d/m/Y') }}</td>
                        <td>
                            <span style="padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600;
                                @if($factura->estado === 'pagada') background: rgba(144, 238, 144, 0.2); color: #90ee90;
                                @elseif($factura->estado === 'parcial') background: rgba(254, 215, 170, 0.2); color: #fed7aa;
                                @else background: rgba(135, 206, 250, 0.2); color: #87cefa; @endif">
                                {{ ucfirst($factura->estado) }}
                            </span>
                        </td>
                        <td style="color: #f0f0f0; font-weight: 600;">{{ number_format($factura->total ?? 0, 2) }}€</td>
                        <td style="display: flex; gap: 0.5rem;">
                            <button class="btn btn-sm btn-outline-info" onclick="window.verFactura({{ $factura->id }})" style="border-color: #87cefa; color: #87cefa; font-size: 0.75rem;">
                                👁️ Ver
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="window.eliminarFactura({{ $factura->id }})" style="border-color: #e63946; color: #e63946; font-size: 0.75rem;">
                                🗑️ Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: #a0a0a0; padding: 2rem;">
                            No hay facturas registradas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <p id="contador" style="color: #a0a0a0; margin-top: 1rem; text-align: center;">
        <strong>Mostrando {{ count($facturas) }} facturas</strong>
    </p>
</div>

<!-- ============ MODAL CREAR FACTURA ============ -->
<div class="modal fade" id="modalFactura" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3);">
            <form action="{{ route('facturas-compra.store') }}" method="POST" id="formFactura">
                @csrf

                <!-- HEADER -->
                <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946;">
                    <h5 class="modal-title" style="color: #e63946; font-weight: 700;">Crear Nueva Factura de Compra</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body" style="color: #f0f0f0; padding: 1.5rem;">

                    <!-- SECCIÓN 1: INFO GENERAL -->
                    <div class="row mb-3" style="gap: 0.5rem;">
                        <div class="col">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Nº Factura</label>
                            <input type="text" name="numero_factura" id="numeroFactura" class="form-control form-control-sm" readonly style="background: rgba(100, 100, 100, 0.2); font-size: 0.9rem;">
                        </div>

                        <div class="col">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Albarán *</label>
                            <input type="text" id="buscadorAlbaran" class="form-control form-control-sm" placeholder="Buscar albarán..." autocomplete="off" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;">
                            <input type="hidden" name="albaran_compra_id" id="albaranSelect" required>
                            <div id="listaAlbaranes" class="list-group" style="max-height: 150px; overflow-y: auto; display: none; position: absolute; width: 140px; z-index: 1000; background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3); border-radius: 6px; margin-top: 0.25rem;"></div>
                        </div>

                        <div class="col">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Fecha Factura *</label>
                            <input type="date" name="fecha_factura" id="fechaFactura" class="form-control form-control-sm" value="{{ today() }}" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;">
                        </div>

                        <div class="col">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Fecha Vencimiento *</label>
                            <input type="date" name="fecha_vencimiento" id="fechaVencimiento" class="form-control form-control-sm" value="{{ today()->addDays(30) }}" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;">
                        </div>
                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1rem 0;">

                    <!-- SECCIÓN 2: TABLA PRODUCTOS -->
                    <h6 style="color: #e63946; font-weight: 700; font-size: 0.9rem; margin-bottom: 0.75rem;">Productos a Facturar</h6>
                    <div class="table-responsive mb-2" style="border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 6px;">
                        <table class="table table-sm" id="tablaProductos" style="margin: 0;">
                            <thead style="background: rgba(230, 57, 70, 0.15);">
                                <tr>
                                    <th style="color: #e63946; width: 30%; font-size: 0.8rem;">Producto</th>
                                    <th style="color: #e63946; width: 15%; font-size: 0.8rem;">Cantidad</th>
                                    <th style="color: #e63946; width: 15%; font-size: 0.8rem;">Precio Unit.</th>
                                    <th style="color: #e63946; width: 15%; font-size: 0.8rem;">IVA (%)</th>
                                    <th style="color: #e63946; width: 15%; font-size: 0.8rem;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="productosListFactura"></tbody>
                        </table>
                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1rem 0;">

                    <!-- SECCIÓN 3: TOTALES -->
                    <div class="row" style="gap: 1rem;">
                        <div class="col-md-6 offset-md-6">
                            <div style="background: rgba(230, 57, 70, 0.1); padding: 1rem; border-radius: 6px; border-left: 3px solid #e63946;">
                                <div class="d-flex justify-content-between align-items-center mb-2" style="font-size: 0.9rem;">
                                    <span style="color: #a0a0a0;">SUBTOTAL:</span>
                                    <strong id="subtotalFacDisplaytural" style="color: #f0f0f0;">0.00€</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center" style="font-size: 1.1rem; padding-top: 0.75rem; border-top: 1px solid rgba(230, 57, 70, 0.3);">
                                    <span style="color: #e63946; font-weight: 700;">TOTAL:</span>
                                    <strong id="totalFactura" style="color: #e63946; font-size: 1.3rem;">0.00€</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1rem 0;">

                    <!-- SECCIÓN 4: OBSERVACIONES -->
                    <label class="form-label fw-bold" style="font-size: 0.85rem;">Observaciones</label>
                    <textarea name="observaciones" class="form-control form-control-sm" rows="2" placeholder="Notas sobre la factura..." style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;"></textarea>

                    <!-- HIDDEN INPUTS -->
                    <input type="hidden" name="subtotal" id="subtotal_hidden" value="0">
                    <input type="hidden" name="total" id="total_hidden" value="0">

                </div>

                <!-- FOOTER -->
                <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3);">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm" style="background: #e63946; border: none;">
                        ✅ Guardar Factura
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============ MODAL VER FACTURA ============ -->
<div class="modal fade" id="modalVerFactura" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3);">
            <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946;">
                <h5 class="modal-title" style="color: #e63946; font-weight: 700;">Detalles de la Factura</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="color: #f0f0f0;">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong style="color: #e63946;">Nº Factura:</strong></p>
                        <p id="ver_numero" style="margin-bottom: 1rem;">—</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong style="color: #e63946;">Albarán:</strong></p>
                        <p id="ver_albaran" style="margin-bottom: 1rem;">—</p>
                    </div>
                </div>
                <hr style="border-color: rgba(230, 57, 70, 0.3);">
                <h6 style="color: #e63946; font-weight: 700; margin-bottom: 1rem;">Productos Facturados</h6>
                <div class="table-responsive" style="border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 6px;">
                    <table class="table table-sm" style="margin: 0;">
                        <thead style="background: rgba(230, 57, 70, 0.15);">
                            <tr>
                                <th style="color: #e63946;">Producto</th>
                                <th style="color: #e63946;">Cantidad</th>
                                <th style="color: #e63946;">Precio Unit.</th>
                                <th style="color: #e63946;">Total</th>
                            </tr>
                        </thead>
                        <tbody id="detallesBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3);">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- INYECTAR DATOS -->
<script>
    window.albaranesData = @json($albaranes);
    window.ultimoFacturaId = @json($ultimoFacturaId ?? 0);
</script>

@endsection

@section('extra-js')

<!-- ✅ SCRIPT DE BÚSQUEDA Y FUNCIONES GLOBALES -->
<script>
    // ✅ BUSCADOR DE ALBARANES
    const albaranesData = @json($albaranes->map(fn($a) => [
        'id' => $a->id,
        'numero' => $a->numero_albaran,
        'proveedor' => $a->proveedor->nombre ?? 'Sin proveedor'
    ])->values());

    const inputBuscadorAlbaran = document.getElementById('buscadorAlbaran');
    const inputHiddenAlbaran = document.getElementById('albaranSelect');
    const listaAlbaranes = document.getElementById('listaAlbaranes');

    if (inputBuscadorAlbaran) {
        inputBuscadorAlbaran.addEventListener('input', function() {
            const valor = this.value.toLowerCase();
            if (valor.length === 0) {
                listaAlbaranes.style.display = 'none';
                return;
            }

            const filtrados = albaranesData.filter(a => 
                a.numero.toLowerCase().includes(valor) || 
                a.proveedor.toLowerCase().includes(valor)
            );

            listaAlbaranes.innerHTML = '';
            if (filtrados.length === 0) {
                listaAlbaranes.innerHTML = '<div class="list-group-item" style="color: #a0a0a0; padding: 0.75rem;">No hay albaranes</div>';
            } else {
                filtrados.forEach(a => {
                    const item = document.createElement('div');
                    item.className = 'list-group-item';
                    item.style.cssText = 'color: #f0f0f0; cursor: pointer; padding: 0.5rem; border-bottom: 1px solid rgba(230, 57, 70, 0.1);';
                    item.innerHTML = `<div><strong style="color: #e63946;">${a.numero}</strong></div><div style="font-size: 0.85rem; color: #a0a0a0;">${a.proveedor}</div>`;
                    item.addEventListener('click', () => {
                        inputBuscadorAlbaran.value = `${a.numero} - ${a.proveedor}`;
                        inputHiddenAlbaran.value = a.id;
                        listaAlbaranes.style.display = 'none';
                        // Aquí irá la carga de productos del albarán
                    });
                    listaAlbaranes.appendChild(item);
                });
            }
            listaAlbaranes.style.display = 'block';
        });

        document.addEventListener('click', (e) => {
            if (e.target !== inputBuscadorAlbaran) {
                listaAlbaranes.style.display = 'none';
            }
        });
    }

    // ✅ VER FACTURA
    window.verFactura = function(id) {
        fetch(`/compras/facturas/${id}/json`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('ver_numero').textContent = data.numero_factura;
                document.getElementById('ver_albaran').textContent = data.numero_albaran;

                const tbody = document.getElementById('detallesBody');
                tbody.innerHTML = '';
                data.detalles.forEach(d => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${d.producto_nombre}</td>
                        <td>${d.cantidad}</td>
                        <td>${d.precio_unitario.toFixed(2)}€</td>
                        <td>${(d.cantidad * d.precio_unitario).toFixed(2)}€</td>
                    `;
                    tbody.appendChild(tr);
                });
                new bootstrap.Modal(document.getElementById('modalVerFactura')).show();
            })
            .catch(err => alert('Error: ' + err));
    };

    // ✅ ELIMINAR FACTURA
    window.eliminarFactura = function(id) {
        if (confirm('¿Seguro que deseas eliminar esta factura?')) {
            fetch(`/compras/facturas/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.ok ? window.location.reload() : alert('Error'))
            .catch(err => alert('Error: ' + err));
        }
    };

    // ✅ BUSCADOR DE TABLA
    document.addEventListener('DOMContentLoaded', function() {
        const buscador = document.getElementById('buscador');
        if (buscador) {
            buscador.addEventListener('keyup', function() {
                const filtro = this.value.toLowerCase();
                const filas = document.querySelectorAll('#tabla-facturas tr');
                let visibles = 0;
                filas.forEach(fila => {
                    const numero = fila.dataset.numero?.toLowerCase() || '';
                    if (numero.includes(filtro)) {
                        fila.style.display = '';
                        visibles++;
                    } else {
                        fila.style.display = 'none';
                    }
                });
                document.getElementById('contador').innerHTML = `<strong>Mostrando ${visibles} de ${filas.length} facturas</strong>`;
            });
        }
    });
</script>

@endsection
