@extends('layouts.app')

@section('title', 'Proveedores')

@section('content')

<!-- HEADER PERSONALIZADO -->
<div style="background: linear-gradient(to right, #0d0d0e 0%, #111111 100%); border-bottom: 2px solid #e63946; padding: 1.5rem 0; margin: -30px -30px 30px -30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 30px; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 50px; height: 50px; background-color: #e63946; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 28px; color: white;">🏢</div>
            <div>
                <h1 style="font-size: 1.8rem; font-weight: bold; color: #f0f0f0; margin: 0;">GESTIÓN DE PROVEEDORES</h1>
                <p style="font-size: 0.75rem; color: #a0a0a0; margin: 0;">{{ $config->nombre_empresa ?? 'RoninTech' }} - Compras</p>
            </div>
        </div>
    </div>
</div>

<!-- BARRA DE ACCIONES -->
<div style="background-color: rgba(20, 20, 25, 0.65); border-bottom: 2px solid #e63946; padding: 1.5rem 0; margin: -30px -30px 30px -30px; backdrop-filter: blur(20px);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <button class="btn btn-danger" style="font-size: 0.85rem; padding: 0.5rem 1rem;" data-bs-toggle="modal" data-bs-target="#modalProveedor">
            <i class="bi bi-plus-lg"></i> Crear Nuevo Proveedor
        </button>
        <input type="text" id="buscador" class="form-control" style="max-width: 300px; background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.85rem;" placeholder="Buscar por nombre...">
    </div>
</div>

<!-- MAIN CONTENT -->
<div style="max-width: 1200px; margin: 0 auto;">

    @if(session('success'))
        <div class="alert alert-success" style="background: rgba(76, 175, 80, 0.15); border: 1px solid #4caf50; color: #90ee90; margin-bottom: 1.5rem;">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- TABLA DE PROVEEDORES -->
    <div style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);">
        <div class="table-responsive">
            <table class="table table-dark table-hover" style="margin-bottom: 0; --bs-table-border-color: rgba(230, 57, 70, 0.1);">
                <thead style="background: rgba(230, 57, 70, 0.15);">
                    <tr>
                        <th style="color: #e63946; font-weight: 700; font-size: 0.85rem; padding: 1rem 0.75rem; text-transform: uppercase;"><i class="bi bi-shop"></i> Nombre</th>
                        <th style="color: #e63946; font-weight: 700; font-size: 0.85rem; padding: 1rem 0.75rem; text-transform: uppercase;"><i class="bi bi-person"></i> Contacto</th>
                        <th style="color: #e63946; font-weight: 700; font-size: 0.85rem; padding: 1rem 0.75rem; text-transform: uppercase;"><i class="bi bi-envelope"></i> Email</th>
                        <th style="color: #e63946; font-weight: 700; font-size: 0.85rem; padding: 1rem 0.75rem; text-transform: uppercase;"><i class="bi bi-geo-alt"></i> Dirección</th>
                        <th style="color: #e63946; font-weight: 700; font-size: 0.85rem; padding: 1rem 0.75rem; text-transform: uppercase;"><i class="bi bi-info-circle"></i> Estado</th>
                        <th style="color: #e63946; font-weight: 700; font-size: 0.85rem; padding: 1rem 0.75rem; text-transform: uppercase; text-align: center;"><i class="bi bi-gear"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-proveedores" style="color: #f0f0f0;">
                    @foreach($proveedores as $p)
                        <tr data-estado="{{ $p->activo ? 'activo' : 'inactivo' }}" data-nombre="{{ $p->nombre }}" style="border-bottom: 1px solid rgba(230, 57, 70, 0.1); transition: background 0.2s;">
                            <td style="padding: 1rem 0.75rem; font-size: 0.9rem;" class="nombre"><i class="bi bi-building" style="color: #e63946; margin-right: 0.5rem;"></i>{{ $p->nombre }}</td>
                            <td style="padding: 1rem 0.75rem; font-size: 0.9rem; color: #b0b0b0;">{{ $p->contacto_nombre ?? '—' }}</td>
                            <td style="padding: 1rem 0.75rem; font-size: 0.9rem; color: #b0b0b0;">{{ $p->email ?? '—' }}</td>
                            <td style="padding: 1rem 0.75rem; font-size: 0.9rem; color: #b0b0b0;">{{ $p->direccion ?? '—' }}</td>
                            <td style="padding: 1rem 0.75rem; font-size: 0.9rem;">
                                <span class="badge px-3 py-2" style="font-size: 0.75rem; font-weight: 700; @if($p->activo) background: #4caf50; color: #c8e6c9; @else background: #666; color: #aaa; @endif">
                                    <i class="bi @if($p->activo) bi-check-circle-fill @else bi-x-circle @endif me-1"></i>{{ $p->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td style="padding: 1rem 0.75rem; text-align: center;">
                                <select class="form-select form-select-sm accion-proveedor" data-id="{{ $p->id }}" style="background: rgba(100, 100, 100, 0.2); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.8rem; padding: 0.4rem 0.6rem;">
                                    <option value="">⚙️ Acciones</option>
                                    <option value="ver">👁️ Ver</option>
                                    <option value="editar">✏️ Editar</option>
                                    <option value="eliminar">🗑️ Eliminar</option>
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ============ MODAL CREAR PROVEEDOR ============ -->
<div class="modal fade" id="modalProveedor" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3); border-radius: 8px;">
            <form action="{{ route('proveedores.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946; padding: 0.75rem 1rem;">
                    <h5 class="modal-title fw-bold" style="color: #e63946; margin: 0; font-size: 1.1rem;">➕ Crear Nuevo Proveedor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="color: #f0f0f0; padding: 1rem; max-height: 70vh; overflow-y: auto;">

                    <!-- SECCIÓN INFO GENERAL -->
                    <div style="display: grid; grid-template-columns: auto 1fr; gap: 0.5rem; align-items: center; margin-bottom: 0.75rem; padding: 0.6rem; background: rgba(230, 57, 70, 0.08); border-radius: 6px; border-left: 3px solid #e63946;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <h6 style="color: #e63946; font-weight: 700; font-size: 0.8rem; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">📋 Información General</h6>
                    </div>

                    <!-- ROW 1: NOMBRE, RUC -->
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 0.4rem; margin-bottom: 0.5rem;">
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Nombre *</label>
                            <input type="text" name="nombre" class="form-control form-control-sm" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                        </div>
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">RUC</label>
                            <input type="text" name="ruc" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                        </div>
                    </div>

                    <!-- ROW 2: EMAIL, TELÉFONO -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem; margin-bottom: 0.5rem;">
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Email</label>
                            <input type="email" name="email" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                        </div>
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Teléfono</label>
                            <input type="text" name="telefono" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                        </div>
                    </div>

                    <!-- ROW 3: DIRECCIÓN -->
                    <div style="margin-bottom: 0.5rem;">
                        <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Dirección</label>
                        <textarea name="direccion" class="form-control form-control-sm" rows="2" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.85rem;"></textarea>
                    </div>

                    <!-- ROW 4: CONTACTO NOMBRE, CONTACTO TELÉFONO -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem; margin-bottom: 0.75rem;">
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Contacto Nombre</label>
                            <input type="text" name="contacto_nombre" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                        </div>
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Contacto Teléfono</label>
                            <input type="text" name="contacto_telefono" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                        </div>
                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 0.75rem 0;">

                    <!-- SECCIÓN FORMA DE PAGO -->
                    <div style="display: grid; grid-template-columns: auto 1fr; gap: 0.5rem; align-items: center; margin-bottom: 0.75rem; padding: 0.6rem; background: rgba(230, 57, 70, 0.08); border-radius: 6px; border-left: 3px solid #e63946;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                        <h6 style="color: #e63946; font-weight: 700; font-size: 0.8rem; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">💳 Forma de Pago Principal *</h6>
                    </div>

                    <!-- FORMA DE PAGO: 3 COLUMNAS -->
                    <div style="display: grid; grid-template-columns: 1.5fr 1fr 1.5fr; gap: 0.4rem; margin-bottom: 0.5rem;">
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Tipo Pago *</label>
                            <select name="forma_pago_id" class="form-select form-select-sm" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                                <option value="">Selecciona...</option>
                                @foreach($formasPago ?? [] as $fp)
                                    <option value="{{ $fp->id }}">{{ $fp->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Banco</label>
                            <select name="banco_id" class="form-select form-select-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                                <option value="">Opcional</option>
                                @foreach($bancos ?? [] as $banco)
                                    <option value="{{ $banco->id }}">{{ $banco->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Referencia (IBAN, etc.)</label>
                            <input type="text" name="referencia" class="form-control form-control-sm" placeholder="ES91 1234 5678..." style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                        </div>
                    </div>

                    <!-- NOMBRE BANCO -->
                    <div style="margin-bottom: 0.75rem;">
                        <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Nombre Banco (Descripción)</label>
                        <input type="text" name="nombre_banco" class="form-control form-control-sm" placeholder="Ej: Cuenta corriente principal" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                    </div>

                    <input type="hidden" name="activo" value="1">

                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3); padding: 0.75rem 1rem;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger btn-sm">💾 Guardar Proveedor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============ MODAL VER PROVEEDOR (CON FORMAS DE PAGO) ============ -->
<div class="modal fade" id="modalVerProveedor" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3); border-radius: 8px;">
            <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946; padding: 1rem;">
                <h5 class="modal-title fw-bold" style="color: #e63946; margin: 0; font-size: 1.1rem;">🏢 Detalles del Proveedor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="color: #f0f0f0; padding: 1rem; max-height: 75vh; overflow-y: auto;">

                <!-- INFO GENERAL -->
                <div style="padding: 0.8rem; background: rgba(230, 57, 70, 0.05); border-left: 3px solid #e63946; border-radius: 6px; margin-bottom: 1rem;">
                    <p style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; margin: 0 0 0.3rem 0; font-weight: 600; letter-spacing: 0.5px;">Nombre</p>
                    <p style="color: #f0f0f0; font-size: 1.1rem; font-weight: 600; margin: 0;" id="ver_nombre">—</p>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                    <div style="padding: 0.8rem; background: rgba(100, 100, 100, 0.1); border-left: 3px solid #e63946; border-radius: 6px;">
                        <p style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; margin: 0 0 0.3rem 0; font-weight: 600;">Contacto</p>
                        <p style="color: #f0f0f0; font-size: 0.9rem; margin: 0;" id="ver_contacto">—</p>
                    </div>
                    <div style="padding: 0.8rem; background: rgba(100, 100, 100, 0.1); border-left: 3px solid #e63946; border-radius: 6px;">
                        <p style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; margin: 0 0 0.3rem 0; font-weight: 600;">Teléfono</p>
                        <p style="color: #f0f0f0; font-size: 0.9rem; margin: 0;" id="ver_telefono">—</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                    <div style="padding: 0.8rem; background: rgba(100, 100, 100, 0.1); border-left: 3px solid #ff9800; border-radius: 6px;">
                        <p style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; margin: 0 0 0.3rem 0; font-weight: 600;">Email</p>
                        <p style="color: #ffb74d; font-size: 0.9rem; margin: 0;" id="ver_email">—</p>
                    </div>
                    <div style="padding: 0.8rem; background: rgba(100, 100, 100, 0.1); border-left: 3px solid #e63946; border-radius: 6px;">
                        <p style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; margin: 0 0 0.3rem 0; font-weight: 600;">Estado</p>
                        <span id="ver_estado" class="badge" style="font-size: 0.8rem;"></span>
                    </div>
                </div>

                <div style="padding: 0.8rem; background: rgba(100, 100, 100, 0.1); border-left: 3px solid #ff9800; border-radius: 6px; margin-bottom: 1rem;">
                    <p style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; margin: 0 0 0.3rem 0; font-weight: 600;">Dirección</p>
                    <p style="color: #ffb74d; font-size: 0.9rem; margin: 0;" id="ver_direccion">—</p>
                </div>

                <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1rem 0;">

                <!-- FORMAS DE PAGO -->
                <h6 style="color: #e63946; font-weight: 700; font-size: 0.9rem; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">💳 Formas de Pago</h6>
                
                <div style="overflow-x: auto; border-radius: 8px; border: 1px solid rgba(230, 57, 70, 0.2); margin-bottom: 1rem;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: rgba(230, 57, 70, 0.15);">
                                <th style="padding: 0.6rem; color: #e63946; text-align: left; font-weight: 700; font-size: 0.8rem; text-transform: uppercase;">Tipo Pago</th>
                                <th style="padding: 0.6rem; color: #e63946; text-align: left; font-weight: 700; font-size: 0.8rem; text-transform: uppercase;">Banco</th>
                                <th style="padding: 0.6rem; color: #e63946; text-align: left; font-weight: 700; font-size: 0.8rem; text-transform: uppercase;">Referencia</th>
                                <th style="padding: 0.6rem; color: #e63946; text-align: left; font-weight: 700; font-size: 0.8rem; text-transform: uppercase;">Nombre</th>
                            </tr>
                        </thead>
                        <tbody id="ver_formas_pago">
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 1rem; color: #a0a0a0; font-size: 0.85rem;">Cargando formas de pago...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3); padding: 1rem;">
                <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal">✕ Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- ============ MODAL EDITAR PROVEEDOR ============ -->
<div class="modal fade" id="modalEditarProveedor" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3); border-radius: 8px;">
            <form id="formEditarProveedor">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946; padding: 0.75rem 1rem;">
                    <h5 class="modal-title fw-bold" style="color: #e63946; margin: 0; font-size: 1.1rem;">✏️ Editar Proveedor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="color: #f0f0f0; padding: 1rem;">
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 0.85rem; color: #e63946;">Nombre</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control form-control-sm" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 0.85rem; color: #e63946;">Dirección</label>
                        <textarea name="direccion" id="edit_direccion" class="form-control form-control-sm" rows="2" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 0.85rem; color: #e63946;">Contacto Nombre</label>
                        <input type="text" name="contacto_nombre" id="edit_contacto_nombre" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 0.85rem; color: #e63946;">Contacto Teléfono</label>
                        <input type="text" name="contacto_telefono" id="edit_contacto_telefono" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 0.85rem; color: #e63946;">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 0.85rem; color: #e63946;">Teléfono</label>
                        <input type="text" name="telefono" id="edit_telefono" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;">
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3); padding: 0.75rem 1rem;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger btn-sm">💾 Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // ==================== ACCIONES (VER, EDITAR, ELIMINAR) ====================
    document.querySelectorAll('.accion-proveedor').forEach(select => {
        select.addEventListener('change', function () {
            const id = this.dataset.id;
            const accion = this.value;
            
            if (accion === 'ver') {
                mostrarProveedor(id);
            }
            
            if (accion === 'editar') {
                editarProveedor(id);
            }
            
            if (accion === 'eliminar') {
                if (confirm('¿Seguro que deseas eliminar este proveedor?')) {
                    fetch(`/proveedores/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => {
                        if (res.ok) {
                            window.location.reload();
                        } else {
                            alert('Error al eliminar proveedor');
                        }
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        alert('Error en la solicitud');
                    });
                }
            }
            
            this.value = '';
        });
    });

    // ==================== VER PROVEEDOR (CON FORMAS DE PAGO) ====================
    function mostrarProveedor(id) {
        fetch(`/proveedores/${id}/json`)
            .then(res => res.json())
            .then(data => {
                // INFO GENERAL
                document.getElementById('ver_nombre').textContent = data.nombre ?? '—';
                document.getElementById('ver_contacto').textContent = data.contacto_nombre ?? '—';
                document.getElementById('ver_telefono').textContent = data.contacto_telefono ?? '—';
                document.getElementById('ver_email').textContent = data.email ?? '—';
                document.getElementById('ver_direccion').textContent = data.direccion ?? '—';
                
                // ESTADO
                const estado = document.getElementById('ver_estado');
                estado.textContent = data.activo ? 'Activo' : 'Inactivo';
                estado.className = data.activo ? 'badge bg-success' : 'badge bg-danger';
                
                // FORMAS DE PAGO
                const tablaFormasPago = document.getElementById('ver_formas_pago');
                
                if (data.formas_pago && data.formas_pago.length > 0) {
                    tablaFormasPago.innerHTML = data.formas_pago.map(fp => `
                        <tr style="border-bottom: 1px solid rgba(230, 57, 70, 0.1);">
                            <td style="padding: 0.6rem; color: #90ee90; font-size: 0.85rem;">${fp.forma_pago?.nombre ?? '—'}</td>
                            <td style="padding: 0.6rem; color: #f0f0f0; font-size: 0.85rem;">${fp.banco?.nombre ?? '—'}</td>
                            <td style="padding: 0.6rem; color: #ffb74d; font-size: 0.85rem; font-family: monospace;">${fp.referencia ?? '—'}</td>
                            <td style="padding: 0.6rem; color: #f0f0f0; font-size: 0.85rem;">${fp.nombre_banco ?? '—'}</td>
                        </tr>
                    `).join('');
                } else {
                    tablaFormasPago.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 1rem; color: #a0a0a0;">Sin formas de pago registradas</td></tr>';
                }
                
                const modal = new bootstrap.Modal(document.getElementById('modalVerProveedor'));
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los datos del proveedor');
            });
    }

    // ==================== EDITAR PROVEEDOR ====================
    function editarProveedor(id) {
        fetch(`/proveedores/${id}/json`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('edit_id').value = data.id;
                document.getElementById('edit_nombre').value = data.nombre;
                document.getElementById('edit_direccion').value = data.direccion ?? '';
                document.getElementById('edit_contacto_nombre').value = data.contacto_nombre ?? '';
                document.getElementById('edit_contacto_telefono').value = data.contacto_telefono ?? '';
                document.getElementById('edit_email').value = data.email ?? '';
                document.getElementById('edit_telefono').value = data.telefono ?? '';
                
                const modal = new bootstrap.Modal(document.getElementById('modalEditarProveedor'));
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los datos del proveedor');
            });
    }

    // ==================== ENVÍO DEL FORMULARIO DE EDICIÓN ====================
    const formEditar = document.getElementById('formEditarProveedor');
    if (formEditar) {
        formEditar.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('edit_id').value;
            const formData = new FormData(this);
            const data = {};
            
            formData.forEach((value, key) => {
                if (key !== '_method' && key !== '_token') {
                    data[key] = value;
                }
            });

            fetch(`/proveedores/${id}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(respuesta => {
                if (respuesta.success) {
                    bootstrap.Modal.getInstance(document.getElementById('modalEditarProveedor')).hide();
                    window.location.reload();
                } else {
                    alert('Error al actualizar: ' + (respuesta.message || 'Error desconocido'));
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Error en la solicitud de actualización');
            });
        });
    }

    // ==================== BUSCADOR ====================
    const buscador = document.getElementById('buscador');
    const filas = document.querySelectorAll('#tabla-proveedores tr');

    if (buscador) {
        buscador.addEventListener('keyup', function () {
            const filtro = this.value.toLowerCase();
            
            filas.forEach(fila => {
                const nombre = fila.querySelector('.nombre')?.textContent.toLowerCase() || '';
                if (nombre.includes(filtro)) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        });
    }
</script>

@endsection