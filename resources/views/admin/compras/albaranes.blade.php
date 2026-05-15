@extends('layouts.app')

@section('title', 'Albaranes de Compra')

@section('content')

<!-- HEADER PERSONALIZADO -->
<div style="background: linear-gradient(to right, #0d0d0e 0%, #111111 100%); border-bottom: 2px solid #e63946; padding: 1.5rem 0; margin: -30px -30px 30px -30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 30px; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 50px; height: 50px; background-color: #e63946; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 28px; color: white;">📦</div>
            <div>
                <h1 style="font-size: 1.8rem; font-weight: bold; color: #f0f0f0; margin: 0;">ALBARANES DE COMPRA</h1>
                <p style="font-size: 0.75rem; color: #a0a0a0; margin: 0;">Gestión de Recepción de Pedidos</p>
            </div>
        </div>
    </div>
</div>

<!-- MENSAJES -->
@if(session('success'))
    <div style="background: rgba(144, 238, 144, 0.15); border-left: 3px solid #90ee90; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
        <span style="color: #90ee90;">✅ {{ session('success') }}</span>
    </div>
@endif

<!-- BARRA DE ACCIONES -->
<div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAlbaran">
        ➕ Crear Nuevo Albarán
    </button>
    <input type="text" id="buscador" class="form-control" placeholder="Buscar por número de albarán..." style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; max-width: 400px;">
</div>

<!-- TABLA DE ALBARANES -->
<div style="max-width: 1200px; margin: 0 auto;">
    <div class="table-responsive" style="border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 6px;">
        <table class="table table-sm" style="margin: 0; background: rgba(20, 20, 25, 0.8);">
            <thead style="background: rgba(230, 57, 70, 0.15);">
                <tr>
                    <th style="color: #e63946; font-weight: 700;">Nº Albarán</th>
                    <th style="color: #e63946; font-weight: 700;">Pedido</th>
                    <th style="color: #e63946; font-weight: 700;">Proveedor</th>
                    <th style="color: #e63946; font-weight: 700;">Fecha Albarán</th>
                    <th style="color: #e63946; font-weight: 700;">Estado</th>
                    <th style="color: #e63946; font-weight: 700;">Total</th>
                    <th style="color: #e63946; font-weight: 700;">Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-albaranes">
                @forelse($albaranes as $albaran)
                    <tr data-numero="{{ $albaran->numero_albaran }}" style="border-bottom: 1px solid rgba(230, 57, 70, 0.1);">
                        <td style="color: #e63946; font-weight: 600;">{{ $albaran->numero_albaran }}</td>
                        <td style="color: #f0f0f0;">{{ $albaran->pedidoCompra->numero_pedido ?? '—' }}</td>
                        <td style="color: #f0f0f0;">{{ $albaran->proveedor->nombre ?? '—' }}</td>
                        <td style="color: #a0a0a0;">{{ $albaran->fecha_albaran->format('d/m/Y') }}</td>
                        <td>
                            <span style="padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600;
                                @if($albaran->estado === 'recibido') background: rgba(144, 238, 144, 0.2); color: #90ee90;
                                @elseif($albaran->estado === 'parcial') background: rgba(254, 215, 170, 0.2); color: #fed7aa;
                                @else background: rgba(135, 206, 250, 0.2); color: #87cefa; @endif">
                                {{ ucfirst($albaran->estado) }}
                            </span>
                        </td>
                        <td style="color: #f0f0f0; font-weight: 600;">{{ number_format($albaran->total ?? 0, 2) }}€</td>
                        <td style="display: flex; gap: 0.5rem;">
                            <button class="btn btn-sm btn-outline-info" onclick="window.verAlbaran({{ $albaran->id }})" style="border-color: #87cefa; color: #87cefa; font-size: 0.75rem;">
                                👁️ Ver
                            </button>
                            <button class="btn btn-sm btn-outline-warning" onclick="window.editarAlbaran({{ $albaran->id }})" style="border-color: #fed7aa; color: #fed7aa; font-size: 0.75rem;">
                                ✏️ Editar
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="window.eliminarAlbaran({{ $albaran->id }})" style="border-color: #e63946; color: #e63946; font-size: 0.75rem;">
                                🗑️ Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #a0a0a0; padding: 2rem;">
                            No hay albaranes registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <p id="contador" style="color: #a0a0a0; margin-top: 1rem; text-align: center;">
        <strong>Mostrando {{ count($albaranes) }} albaranes</strong>
    </p>
</div>

<!-- ============ MODAL CREAR ALBARÁN ============ -->
<div class="modal fade" id="modalAlbaran" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3);">
            <form action="{{ route('albaranes-compra.store') }}" method="POST" id="formAlbaran">
                @csrf
                <!-- HEADER -->
                <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946;">
                    <h5 class="modal-title" style="color: #e63946; font-weight: 700;">Crear Nuevo Albarán de Compra</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <!-- BODY -->
                <div class="modal-body" style="color: #f0f0f0; padding: 1.5rem;">
                    <!-- SECCIÓN 1: INFO GENERAL (4 COLUMNAS) -->
                    <div class="row mb-3" style="gap: 0.5rem;">
                        <div class="col">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Nº Albarán</label>
                            <input type="text" name="numero_albaran" id="numeroAlbaran" class="form-control form-control-sm" readonly style="background: rgba(100, 100, 100, 0.2); font-size: 0.9rem;">
                        </div>
                        <div class="col">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Pedido *</label>
                            <select name="pedido_compra_id" id="pedidoSelect" class="form-select form-select-sm" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;" onchange="window.CrearAlbaranModal.cargarProductosPedido()">
                                <option value="">Selecciona un pedido...</option>
                                @foreach($pedidos as $pedido)
                                    <option value="{{ $pedido->id }}" data-numero="{{ $pedido->numero_pedido }}" data-proveedor="{{ $pedido->proveedor->nombre }}">
                                        {{ $pedido->numero_pedido }} - {{ $pedido->proveedor->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Fecha Albarán *</label>
                            <input type="date" name="fecha_albaran" id="fechaAlbaran" class="form-control form-control-sm" value="{{ today() }}" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;">
                        </div>
                        <div class="col">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Fecha Recepción</label>
                            <input type="date" name="fecha_recepcion" id="fechaRecepcion" class="form-control form-control-sm" value="{{ today() }}" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;">
                        </div>
                    </div>
                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1rem 0;">
                    <!-- SECCIÓN 2: TABLA PRODUCTOS -->
                    <h6 style="color: #e63946; font-weight: 700; font-size: 0.9rem; margin-bottom: 0.75rem;">Productos a Recibir</h6>
                    <div class="table-responsive mb-2" style="border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 6px;">
                        <table class="table table-sm" id="tablaProductos" style="margin: 0;">
                            <thead style="background: rgba(230, 57, 70, 0.15);">
                                <tr>
                                    <th style="color: #e63946; width: 35%; font-size: 0.8rem;">Producto</th>
                                    <th style="color: #e63946; width: 15%; font-size: 0.8rem;">Cant. Pedida</th>
                                    <th style="color: #e63946; width: 15%; font-size: 0.8rem;">Cant. Recibida *</th>
                                    <th style="color: #e63946; width: 15%; font-size: 0.8rem;">Cant. Faltante</th>
                                    <th style="color: #e63946; width: 15%; font-size: 0.8rem;">Estado</th>
                                    <th style="color: #e63946; width: 5%; font-size: 0.8rem;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="productosList"></tbody>
                        </table>
                    </div>
                    <p style="color: #a0a0a0; font-size: 0.8rem; margin-top: 0.5rem;">
                        ✓ La cantidad faltante se calcula automáticamente
                    </p>
                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1rem 0;">
                    <!-- SECCIÓN 3: OBSERVACIONES -->
                    <label class="form-label fw-bold" style="font-size: 0.85rem;">Observaciones</label>
                    <textarea name="observaciones" class="form-control form-control-sm" rows="2" placeholder="Notas sobre la recepción..." style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;"></textarea>
                    <input type="hidden" name="estado" id="estado_hidden" value="recibido">
                </div>
                <!-- FOOTER -->
                <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3);">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm" style="background: #e63946; border: none;">
                        ✅ Guardar Albarán
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============ MODAL VER ALBARÁN (SOLO LECTURA) ============ -->
<div class="modal fade" id="modalVerAlbaran" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3);">
            <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946;">
                <h5 class="modal-title" style="color: #e63946; font-weight: 700;">📋 Detalles del Albarán</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="color: #f0f0f0;">
                <!-- Info General -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong style="color: #e63946;">Nº Albarán:</strong></p>
                        <p id="ver_numero" style="margin-bottom: 1rem;">—</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong style="color: #e63946;">Pedido:</strong></p>
                        <p id="ver_pedido" style="margin-bottom: 1rem;">—</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong style="color: #e63946;">Proveedor:</strong></p>
                        <p id="ver_proveedor" style="margin-bottom: 1rem;">—</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong style="color: #e63946;">Estado:</strong></p>
                        <p id="ver_estado" style="margin-bottom: 1rem;"><span class="badge">—</span></p>
                    </div>
                </div>
                <hr style="border-color: rgba(230, 57, 70, 0.3);">
                <!-- Detalles de Recepción -->
                <h6 style="color: #e63946; font-weight: 700; margin-bottom: 1rem;">Detalles de Recepción</h6>
                <div class="table-responsive" style="border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 6px;">
                    <table class="table table-sm" id="tablaDetalles" style="margin: 0;">
                        <thead style="background: rgba(230, 57, 70, 0.15);">
                            <tr>
                                <th style="color: #e63946;">Producto</th>
                                <th style="color: #e63946;">Pedida</th>
                                <th style="color: #e63946;">Recibida</th>
                                <th style="color: #e63946;">Faltante</th>
                                <th style="color: #e63946;">Estado</th>
                            </tr>
                        </thead>
                        <tbody id="detallesBody"></tbody>
                    </table>
                </div>
                <hr style="border-color: rgba(230, 57, 70, 0.3);">
                <!-- Observaciones -->
                <p><strong style="color: #e63946;">Observaciones:</strong></p>
                <p id="ver_observaciones" style="color: #a0a0a0; font-style: italic;">—</p>
            </div>
            <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3);">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- ============ MODAL EDITAR ALBARÁN ============ -->
<div class="modal fade" id="modalEditarAlbaran" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3);">
            <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946;">
                <h5 class="modal-title" style="color: #e63946; font-weight: 700;">✏️ Editar Albarán</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="color: #f0f0f0;">
                <!-- Info General (Read-only) -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong style="color: #e63946;">Nº Albarán:</strong></p>
                        <p id="edit_numero" style="margin-bottom: 1rem; color: #a0a0a0;">—</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong style="color: #e63946;">Pedido:</strong></p>
                        <p id="edit_pedido" style="margin-bottom: 1rem; color: #a0a0a0;">—</p>
                    </div>
                </div>
                <hr style="border-color: rgba(230, 57, 70, 0.3);">
                <!-- Fechas y Observaciones (Editables) -->
                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size: 0.85rem; color: #e63946;">Fecha Recepción *</label>
                    <input type="date" id="edit_fecha_recepcion" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size: 0.85rem; color: #e63946;">Observaciones</label>
                    <textarea id="edit_observaciones" class="form-control form-control-sm" rows="3" placeholder="Notas sobre la recepción..." style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.9rem;"></textarea>
                </div>
                <hr style="border-color: rgba(230, 57, 70, 0.3);">
                <!-- Detalles de Productos (Read-only) -->
                <h6 style="color: #e63946; font-weight: 700; margin-bottom: 1rem;">📦 Detalles de Recepción</h6>
                <div class="table-responsive" style="border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 6px;">
                    <table class="table table-sm" style="margin: 0;">
                        <thead style="background: rgba(230, 57, 70, 0.15);">
                            <tr>
                                <th style="color: #e63946;">Producto</th>
                                <th style="color: #e63946;">Pedida</th>
                                <th style="color: #e63946;">Recibida</th>
                                <th style="color: #e63946;">Faltante</th>
                                <th style="color: #e63946;">Estado</th>
                            </tr>
                        </thead>
                        <tbody id="edit_detallesBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3);">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="window.guardarCambiosAlbaran()" style="background: #e63946; border: none;">
                    💾 Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

<!-- INYECTAR DATOS -->
<script>
    window.pedidosData = @json($pedidos);
    window.ultimoAlbaranId = @json($ultimoAlbaranId ?? 0);
</script>

@endsection

@section('extra-js')

<script>
    let albaranEnEdicion = null;

    // ✅ VER ALBARÁN (Solo lectura)
    window.verAlbaran = function(id) {
        fetch(`/compras/albaranes/${id}/json`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('ver_numero').textContent = data.numero_albaran;
                document.getElementById('ver_pedido').textContent = data.numero_pedido;
                document.getElementById('ver_proveedor').textContent = data.proveedor;
                document.getElementById('ver_observaciones').textContent = data.observaciones || '—';

                const estadoBadge = document.getElementById('ver_estado').querySelector('.badge');
                estadoBadge.textContent = data.estado.toUpperCase();
                estadoBadge.style.background = data.estado === 'recibido' ? 'rgba(144, 238, 144, 0.2)' : 'rgba(254, 215, 170, 0.2)';
                estadoBadge.style.color = data.estado === 'recibido' ? '#90ee90' : '#fed7aa';

                const tbody = document.getElementById('detallesBody');
                tbody.innerHTML = '';
                data.detalles.forEach(d => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${d.producto_nombre}</td>
                        <td>${d.cantidad_pedida}</td>
                        <td>${d.cantidad_recibida}</td>
                        <td>${d.cantidad_faltante}</td>
                        <td><small style="background: rgba(230, 57, 70, 0.2); padding: 0.2rem 0.5rem; border-radius: 3px; color: #e63946;">${d.estado.toUpperCase()}</small></td>
                    `;
                    tbody.appendChild(tr);
                });

                new bootstrap.Modal(document.getElementById('modalVerAlbaran')).show();
            })
            .catch(err => alert('Error: ' + err));
    };

    // ✅ EDITAR ALBARÁN
    window.editarAlbaran = function(id) {
        albaranEnEdicion = id;
        fetch(`/compras/albaranes/${id}/json`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('edit_numero').textContent = data.numero_albaran;
                document.getElementById('edit_pedido').textContent = data.numero_pedido;
                document.getElementById('edit_fecha_recepcion').value = data.fecha_recepcion || '';
                document.getElementById('edit_observaciones').value = data.observaciones || '';

                const tbody = document.getElementById('edit_detallesBody');
                tbody.innerHTML = '';
                data.detalles.forEach(d => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${d.producto_nombre}</td>
                        <td>${d.cantidad_pedida}</td>
                        <td>${d.cantidad_recibida}</td>
                        <td>${d.cantidad_faltante}</td>
                        <td><small style="background: rgba(230, 57, 70, 0.2); padding: 0.2rem 0.5rem; border-radius: 3px; color: #e63946;">${d.estado.toUpperCase()}</small></td>
                    `;
                    tbody.appendChild(tr);
                });

                new bootstrap.Modal(document.getElementById('modalEditarAlbaran')).show();
            })
            .catch(err => alert('❌ Error: ' + err));
    };

    // ✅ GUARDAR CAMBIOS
    window.guardarCambiosAlbaran = function() {
        if (!albaranEnEdicion) return;

        const fechaRecepcion = document.getElementById('edit_fecha_recepcion').value;
        const observaciones = document.getElementById('edit_observaciones').value;

        if (!fechaRecepcion) {
            alert('⚠️ Debes seleccionar una fecha de recepción');
            return;
        }

        fetch(`/compras/albaranes/${albaranEnEdicion}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                fecha_recepcion: fechaRecepcion,
                observaciones: observaciones
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('✅ Cambios guardados correctamente');
                bootstrap.Modal.getInstance(document.getElementById('modalEditarAlbaran')).hide();
                window.location.reload();
            } else {
                alert('❌ Error: ' + data.message);
            }
        })
        .catch(err => alert('❌ Error: ' + err));
    };

    // ✅ ELIMINAR ALBARÁN
    window.eliminarAlbaran = function(id) {
        if (confirm('¿Seguro que deseas eliminar este albarán?')) {
            fetch(`/compras/albaranes/${id}`, {
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

    // ✅ BUSCADOR
    document.addEventListener('DOMContentLoaded', function() {
        const buscador = document.getElementById('buscador');
        if (buscador) {
            buscador.addEventListener('keyup', function() {
                const filtro = this.value.toLowerCase();
                const filas = document.querySelectorAll('#tabla-albaranes tr');
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
                document.getElementById('contador').innerHTML = `<strong>Mostrando ${visibles} de ${filas.length} albaranes</strong>`;
            });
        }
    });
</script>

@endsection