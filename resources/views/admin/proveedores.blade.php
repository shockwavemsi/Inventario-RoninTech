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

    <p id="contador" style="margin-top: 1rem; color: #a0a0a0;"><strong>Mostrando {{ count($proveedores) }} proveedores</strong></p>

</div>

<!-- ============ MODAL VER PROVEEDOR ============ -->

<div class="modal fade" id="modalVerProveedor" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3); border-radius: 8px;">

            <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946; padding: 0.75rem 1rem;">

                <h5 class="modal-title fw-bold" style="color: #e63946; margin: 0;">ℹ️ Información del Proveedor</h5>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

            </div>

            <div class="modal-body" style="color: #f0f0f0; padding: 1rem;">

                <div style="display: grid; gap: 0.75rem;">

                    <div style="padding: 0.6rem; background: rgba(230, 57, 70, 0.05); border-left: 3px solid #e63946; border-radius: 4px;">

                        <p style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; margin: 0 0 0.2rem 0; font-weight: 600; letter-spacing: 0.5px;">Nombre</p>

                        <p style="color: #f0f0f0; margin: 0; font-weight: 500;" id="ver_nombre">—</p>

                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">

                        <div style="padding: 0.6rem; background: rgba(230, 57, 70, 0.05); border-left: 3px solid #e63946; border-radius: 4px;">

                            <p style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; margin: 0 0 0.2rem 0; font-weight: 600; letter-spacing: 0.5px;">Contacto</p>

                            <p style="color: #f0f0f0; margin: 0; font-size: 0.9rem;" id="ver_contacto">—</p>

                        </div>

                        <div style="padding: 0.6rem; background: rgba(230, 57, 70, 0.05); border-left: 3px solid #e63946; border-radius: 4px;">

                            <p style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; margin: 0 0 0.2rem 0; font-weight: 600; letter-spacing: 0.5px;">Teléfono</p>

                            <p style="color: #f0f0f0; margin: 0; font-size: 0.9rem;" id="ver_telefono">—</p>

                        </div>

                    </div>

                    <div style="padding: 0.6rem; background: rgba(230, 57, 70, 0.05); border-left: 3px solid #e63946; border-radius: 4px;">

                        <p style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; margin: 0 0 0.2rem 0; font-weight: 600; letter-spacing: 0.5px;">Email</p>

                        <p style="color: #f0f0f0; margin: 0; font-size: 0.9rem;" id="ver_email">—</p>

                    </div>

                    <div style="padding: 0.6rem; background: rgba(230, 57, 70, 0.05); border-left: 3px solid #e63946; border-radius: 4px;">

                        <p style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; margin: 0 0 0.2rem 0; font-weight: 600; letter-spacing: 0.5px;">Dirección</p>

                        <p style="color: #f0f0f0; margin: 0; font-size: 0.9rem;" id="ver_direccion">—</p>

                    </div>

                    <div style="padding: 0.6rem; background: rgba(230, 57, 70, 0.05); border-left: 3px solid #e63946; border-radius: 4px;">

                        <p style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; margin: 0 0 0.2rem 0; font-weight: 600; letter-spacing: 0.5px;">Estado</p>

                        <p style="margin: 0;" id="ver_estado"></p>

                    </div>

                    <div style="padding: 0.6rem; background: rgba(255, 152, 0, 0.08); border-left: 3px solid #ff9800; border-radius: 4px;">

                        <p style="font-size: 0.7rem; color: #a0a0a0; text-transform: uppercase; margin: 0 0 0.2rem 0; font-weight: 600; letter-spacing: 0.5px;">⏳ Días de Vencimiento</p>

                        <p style="color: #ffb74d; margin: 0; font-weight: 600; font-size: 1rem;" id="ver_dias_vencimiento">—</p>

                    </div>

                </div>

                <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 1rem 0;">

                <h6 style="color: #e63946; font-weight: 700; font-size: 0.9rem; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">💳 Formas de Pago</h6>

                <div class="table-responsive" style="border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 6px; margin-bottom: 1rem;">

                    <table style="width: 100%; border-collapse: collapse; margin: 0;">

                        <thead style="background: rgba(230, 57, 70, 0.15); border-bottom: 1px solid rgba(230, 57, 70, 0.2);">

                            <tr>

                                <th style="padding: 0.6rem; color: #e63946; text-align: left; font-weight: 700; font-size: 0.8rem; text-transform: uppercase;">Tipo Pago</th>

                                <th style="padding: 0.6rem; color: #e63946; text-align: left; font-weight: 700; font-size: 0.8rem; text-transform: uppercase;">Banco</th>

                                <th style="padding: 0.6rem; color: #e63946; text-align: left; font-weight: 700; font-size: 0.8rem; text-transform: uppercase;">Referencia</th>

                                <th style="padding: 0.6rem; color: #e63946; text-align: left; font-weight: 700; font-size: 0.8rem; text-transform: uppercase;">Descripción</th>

                            </tr>

                        </thead>

                        <tbody id="tabla_formas_pago">

                            <tr>

                                <td colspan="4" style="padding: 1rem; text-align: center; color: #a0a0a0;">Cargando formas de pago...</td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

            <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3); padding: 0.75rem 1rem;">

                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>

            </div>

        </div>

    </div>

</div>

<!-- ============ MODAL EDITAR PROVEEDOR - DINÁMICO ============ -->

<div class="modal fade" id="modalEditarProveedor" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3); border-radius: 8px;">

            <form id="formEditarProveedor">

                @csrf

                @method('PUT')

                <input type="hidden" id="edit_id" name="id">

                <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946; padding: 0.75rem 1rem;">

                    <h5 class="modal-title fw-bold" style="color: #e63946; margin: 0; font-size: 1.1rem;">✏️ Editar Proveedor</h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body" style="color: #f0f0f0; padding: 1rem; max-height: 75vh; overflow-y: auto;">

                    <!-- SECCIÓN: INFO GENERAL -->

                    <div style="display: grid; grid-template-columns: auto 1fr; gap: 0.5rem; align-items: center; margin-bottom: 0.75rem; padding: 0.6rem; background: rgba(230, 57, 70, 0.08); border-radius: 6px; border-left: 3px solid #e63946;">

                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>

                        <h6 style="color: #e63946; font-weight: 700; font-size: 0.8rem; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">📋 Información General</h6>

                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem; margin-bottom: 0.5rem;">

                        <div>

                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Nombre *</label>

                            <input type="text" name="nombre" id="edit_nombre" class="form-control form-control-sm" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">

                        </div>

                        <div>

                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">CIF</label>

                            <input type="text" name="ruc" id="edit_ruc" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">

                        </div>

                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem; margin-bottom: 0.5rem;">

                        <div>

                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Email</label>

                            <input type="email" name="email" id="edit_email" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">

                        </div>

                        <div>

                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Teléfono</label>

                            <input type="text" name="telefono" id="edit_telefono" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">

                        </div>

                    </div>

                    <div style="margin-bottom: 0.5rem;">

                        <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Dirección</label>

                        <textarea name="direccion" id="edit_direccion" class="form-control form-control-sm" rows="2" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.8rem;"></textarea>

                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem; margin-bottom: 0.5rem;">

                        <div>

                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Contacto Nombre</label>

                            <input type="text" name="contacto_nombre" id="edit_contacto_nombre" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">

                        </div>

                        <div>

                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Contacto Teléfono</label>

                            <input type="text" name="contacto_telefono" id="edit_contacto_telefono" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">

                        </div>

                    </div>

                    <div style="margin-bottom: 0.5rem;">

                        <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Días de Vencimiento</label>

                        <input type="number" name="dias_vencimiento" id="edit_dias_vencimiento" class="form-control form-control-sm" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">

                    </div>

                    <div style="margin-bottom: 0.5rem;">

                        <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Estado</label>

                        <select name="activo" id="edit_activo" class="form-select form-select-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">

                            <option value="1">Activo</option>

                            <option value="0">Inactivo</option>

                        </select>

                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 0.75rem 0;">

                    <!-- SECCIÓN: FORMAS DE PAGO (DINÁMICO) -->

                    <div style="display: grid; grid-template-columns: auto 1fr auto; gap: 0.5rem; align-items: center; margin-bottom: 0.75rem; padding: 0.6rem; background: rgba(230, 57, 70, 0.08); border-radius: 6px; border-left: 3px solid #e63946;">

                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>

                        <h6 style="color: #e63946; font-weight: 700; font-size: 0.8rem; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">💳 Formas de Pago</h6>

                        <button type="button" class="btn btn-sm btn-outline-danger" id="btnAgregarFormaEditarr" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">+ Agregar</button>

                    </div>

                    <!-- CONTENEDOR DINÁMICO DE FORMAS DE PAGO -->

                    <div id="contenedorFormasEditarr" style="border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 6px; padding: 0.75rem; background: rgba(230, 57, 70, 0.02);">

                        <!-- Las formas de pago se cargan aquí dinámicamente -->

                    </div>

                    <p style="font-size: 0.7rem; color: #a0a0a0; margin-top: 0.5rem; margin-bottom: 0;"><strong>ℹ️ Nota:</strong> Mínimo 1 forma de pago. Puedes agregar o modificar las existentes.</p>

                </div>

                <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3); padding: 0.75rem 1rem;">

                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>

                    <button type="submit" class="btn btn-danger btn-sm">💾 Guardar Cambios</button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- ============ MODAL CREAR PROVEEDOR CON FORMAS DE PAGO DINÁMICAS ============ -->

<div class="modal fade" id="modalProveedor" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3); border-radius: 8px;">

            <form action="{{ route('proveedores.store') }}" method="POST" id="formCrearProveedor">

                @csrf

                <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946; padding: 0.75rem 1rem;">

                    <h5 class="modal-title fw-bold" style="color: #e63946; margin: 0; font-size: 1.1rem;">➕ Crear Nuevo Proveedor</h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body" style="color: #f0f0f0; padding: 1rem; max-height: 75vh; overflow-y: auto;">

                    <!-- SECCIÓN: INFO GENERAL -->

                    <div style="display: grid; grid-template-columns: auto 1fr; gap: 0.5rem; align-items: center; margin-bottom: 0.75rem; padding: 0.6rem; background: rgba(230, 57, 70, 0.08); border-radius: 6px; border-left: 3px solid #e63946;">

                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>

                        <h6 style="color: #e63946; font-weight: 700; font-size: 0.8rem; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">📋 Información General</h6>

                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem; margin-bottom: 0.5rem;">

                        <div>

                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Nombre *</label>

                            <input type="text" name="nombre" class="form-control form-control-sm" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">

                        </div>

                        <div>

                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">CIF</label>

                            <input type="text" name="ruc" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">

                        </div>

                    </div>

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

                    <div style="margin-bottom: 0.5rem;">

                        <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Dirección</label>

                        <textarea name="direccion" class="form-control form-control-sm" rows="2" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.8rem;"></textarea>

                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem; margin-bottom: 0.5rem;">

                        <div>

                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Contacto Nombre</label>

                            <input type="text" name="contacto_nombre" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">

                        </div>

                        <div>

                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Contacto Teléfono</label>

                            <input type="text" name="contacto_telefono" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">

                        </div>

                    </div>

                    <div style="margin-bottom: 0.5rem;">

                        <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Días de Vencimiento</label>

                        <input type="number" name="dias_vencimiento" class="form-control form-control-sm" value="30" min="1" max="365" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">

                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 0.75rem 0;">

                    <!-- SECCIÓN: FORMAS DE PAGO (DINÁMICO) -->

                    <div style="display: grid; grid-template-columns: auto 1fr auto; gap: 0.5rem; align-items: center; margin-bottom: 0.75rem; padding: 0.6rem; background: rgba(230, 57, 70, 0.08); border-radius: 6px; border-left: 3px solid #e63946;">

                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>

                        <h6 style="color: #e63946; font-weight: 700; font-size: 0.8rem; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">💳 Formas de Pago (Mínimo 1)</h6>

                        <button type="button" class="btn btn-sm btn-outline-danger" id="btnAgregarFormaPago" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">+ Agregar</button>

                    </div>

                    <!-- CONTENEDOR DINÁMICO DE FORMAS DE PAGO -->

                    <div id="contenedorFormasPago" style="border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 6px; padding: 0.75rem; background: rgba(230, 57, 70, 0.02);">

                        <!-- Las formas de pago se agregan aquí dinámicamente -->

                    </div>

                    <p style="font-size: 0.7rem; color: #a0a0a0; margin-top: 0.5rem; margin-bottom: 0;"><strong>ℹ️ Nota:</strong> Mínimo 1 forma de pago obligatoria.</p>

                </div>

                <div style="border-top: 1px solid #e63946; padding: 0.75rem 1rem; background: rgba(230, 57, 70, 0.05); border-radius: 0 0 8px 8px;">

                    <div id="estadoValidacion" style="font-size: 0.75rem; color: #ff6b6b; margin-bottom: 0.5rem; display: none; font-weight: 600;">

                        ⚠️ Mínimo 1 forma de pago completa

                    </div>

                </div>

                <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3); padding: 0.75rem 1rem;">

                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>

                    <button type="submit" class="btn btn-danger btn-sm">💾 Guardar Proveedor</button>

                </div>

            </form>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

    // ============ INICIALIZACIÓN ============

    let contadorFormasPago = 0;
    let contadorFormasEditar = 0;

    const formasPagoOptions = @json($formasPago ?? []);

    const bancosOptions = @json($bancos ?? []);

    // Crear 1 forma de pago inicial cuando se carga la página

    document.addEventListener('DOMContentLoaded', function() {

        agregarFormaPago();

    });

    // ============ RESETEAR MODAL AL ABRIR ============

    const modalProveedor = document.getElementById('modalProveedor');

    if (modalProveedor) {

        modalProveedor.addEventListener('show.bs.modal', function() {

            // Limpiar y reinicializar TODO

            contadorFormasPago = 0;

            document.getElementById('contenedorFormasPago').innerHTML = '';

            document.getElementById('formCrearProveedor').reset();

            agregarFormaPago(); // Agregar una forma por defecto

        });

    }

    // ============ RESETEAR MODAL EDITAR AL ABRIR ============

    const modalEditarProveedor = document.getElementById('modalEditarProveedor');

    if (modalEditarProveedor) {

        modalEditarProveedor.addEventListener('show.bs.modal', function() {

            // Se resetea en editarProveedor() después de cargar datos

        });

    }

    // ============ FUNCIONES DINÁMICAS - CREAR ============

    function agregarFormaPago() {

        contadorFormasPago++;

        const index = contadorFormasPago;

        const contenedor = document.getElementById('contenedorFormasPago');

        const html = `

            <div class="forma-pago-item" id="formaPago_${index}" style="padding: 0.75rem; background: rgba(20, 20, 25, 0.5); border: 1px solid rgba(230, 57, 70, 0.15); border-radius: 6px; margin-bottom: 0.5rem; position: relative;">

                <button type="button" class="btn btn-sm btn-danger" style="position: absolute; top: 0.25rem; right: 0.25rem; padding: 0.25rem 0.35rem; font-size: 0.7rem;" onclick="eliminarFormaPago(${index})">✕</button>

                <p style="color: #e63946; font-size: 0.75rem; font-weight: 700; margin: 0 0 0.5rem 0; text-transform: uppercase;">Forma de Pago #${index}</p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem; margin-bottom: 0.4rem;">

                    <div>

                        <label class="form-label fw-bold" style="font-size: 0.7rem; color: #e63946; margin-bottom: 0.1rem;">Tipo Pago *</label>

                        <select name="forma_pago_id[]" class="form-select form-select-sm" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 28px; font-size: 0.75rem;">

                            <option value="">Selecciona...</option>

                            ${formasPagoOptions.map(fp => `<option value="${fp.id}">${fp.nombre}</option>`).join('')}

                        </select>

                    </div>

                    <div>

                        <label class="form-label fw-bold" style="font-size: 0.7rem; color: #e63946; margin-bottom: 0.1rem;">Banco</label>

                        <select name="banco_id[]" class="form-select form-select-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 28px; font-size: 0.75rem;">

                            <option value="">Selecciona...</option>

                            ${bancosOptions.map(b => `<option value="${b.id}">${b.nombre}</option>`).join('')}

                        </select>

                    </div>

                </div>

                <div style="margin-bottom: 0.4rem;">

                    <label class="form-label fw-bold" style="font-size: 0.7rem; color: #e63946; margin-bottom: 0.1rem;">Referencia</label>

                    <input type="text" name="referencia[]" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 28px; font-size: 0.75rem;" placeholder="ES91 1234 5678...">

                </div>

                <div>

                    <label class="form-label fw-bold" style="font-size: 0.7rem; color: #e63946; margin-bottom: 0.1rem;">Descripción</label>

                    <input type="text" name="nombre_banco[]" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 28px; font-size: 0.75rem;" placeholder="Ej: Cuenta corriente principal">

                </div>

            </div>

        `;

        contenedor.insertAdjacentHTML('beforeend', html);

        validarFormasPago();

    }

    function eliminarFormaPago(index) {

        const elemento = document.getElementById(`formaPago_${index}`);

        if (elemento) {

            elemento.remove();

            validarFormasPago();

        }

    }

    function validarFormasPago() {

        const formas = document.querySelectorAll('.forma-pago-item');

        const estadoDiv = document.getElementById('estadoValidacion');

        let completas = 0;

        formas.forEach(forma => {

            const select = forma.querySelector('select[name="forma_pago_id[]"]');

            if (select && select.value) {

                completas++;

            }

        });

        if (completas === 0) {

            estadoDiv.style.display = 'block';

        } else {

            estadoDiv.style.display = 'none';

        }

    }

    // Validar al cambiar formas de pago

    document.addEventListener('change', function(e) {

        if (e.target.name === 'forma_pago_id[]') {

            validarFormasPago();

        }

    });

    // Botón agregar forma (CREAR)

    const btnAgregar = document.getElementById('btnAgregarFormaPago');

    if (btnAgregar) {

        btnAgregar.addEventListener('click', function(e) {

            e.preventDefault();

            agregarFormaPago();

        });

    }

    // Botón agregar forma (EDITAR)

    const btnAgregarEditar = document.getElementById('btnAgregarFormaEditarr');

    if (btnAgregarEditar) {

        btnAgregarEditar.addEventListener('click', function(e) {

            e.preventDefault();

            agregarFormaEditarr();

        });

    }

    // ============ FUNCIONES DINÁMICAS - EDITAR ============

    function agregarFormaEditarr() {

        contadorFormasEditar++;

        const index = contadorFormasEditar;

        const contenedor = document.getElementById('contenedorFormasEditarr');

        const html = `

            <div class="forma-pago-editarr" id="formaEditarr_${index}" style="padding: 0.75rem; background: rgba(20, 20, 25, 0.5); border: 1px solid rgba(230, 57, 70, 0.15); border-radius: 6px; margin-bottom: 0.5rem; position: relative;">

                <button type="button" class="btn btn-sm btn-danger" style="position: absolute; top: 0.25rem; right: 0.25rem; padding: 0.25rem 0.35rem; font-size: 0.7rem;" onclick="eliminarFormaEditarr(${index})">✕</button>

                <p style="color: #e63946; font-size: 0.75rem; font-weight: 700; margin: 0 0 0.5rem 0; text-transform: uppercase;">Forma de Pago #${index}</p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem; margin-bottom: 0.4rem;">

                    <div>

                        <label class="form-label fw-bold" style="font-size: 0.7rem; color: #e63946; margin-bottom: 0.1rem;">Tipo Pago *</label>

                        <select name="forma_pago_id[]" class="form-select form-select-sm" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 28px; font-size: 0.75rem;">

                            <option value="">Selecciona...</option>

                            ${formasPagoOptions.map(fp => `<option value="${fp.id}">${fp.nombre}</option>`).join('')}

                        </select>

                    </div>

                    <div>

                        <label class="form-label fw-bold" style="font-size: 0.7rem; color: #e63946; margin-bottom: 0.1rem;">Banco</label>

                        <select name="banco_id[]" class="form-select form-select-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 28px; font-size: 0.75rem;">

                            <option value="">Selecciona...</option>

                            ${bancosOptions.map(b => `<option value="${b.id}">${b.nombre}</option>`).join('')}

                        </select>

                    </div>

                </div>

                <div style="margin-bottom: 0.4rem;">

                    <label class="form-label fw-bold" style="font-size: 0.7rem; color: #e63946; margin-bottom: 0.1rem;">Referencia</label>

                    <input type="text" name="referencia[]" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 28px; font-size: 0.75rem;" placeholder="ES91 1234 5678...">

                </div>

                <div>

                    <label class="form-label fw-bold" style="font-size: 0.7rem; color: #e63946; margin-bottom: 0.1rem;">Descripción</label>

                    <input type="text" name="nombre_banco[]" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 28px; font-size: 0.75rem;" placeholder="Ej: Cuenta corriente principal">

                </div>

            </div>

        `;

        contenedor.insertAdjacentHTML('beforeend', html);

    }

    function eliminarFormaEditarr(index) {

        const elemento = document.getElementById(`formaEditarr_${index}`);

        if (elemento) {

            elemento.remove();

        }

    }

    // ============ VALIDACIÓN AL ENVIAR - CREAR ============

    const formCrear = document.getElementById('formCrearProveedor');

    if (formCrear) {

        formCrear.addEventListener('submit', function(e) {

            const formas = document.querySelectorAll('.forma-pago-item');

            let completas = 0;

            formas.forEach(forma => {

                const select = forma.querySelector('select[name="forma_pago_id[]"]');

                if (select && select.value) {

                    completas++;

                }

            });

            if (completas === 0) {

                e.preventDefault();

                alert('⚠️ Agrega al menos 1 forma de pago');

            }

        });

    }

    // ============ ACCIONES EN LA TABLA ============

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

                            alert('Error al eliminar');

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

    // ============ MOSTRAR PROVEEDOR ============

    function mostrarProveedor(id) {

        fetch(`/proveedores/${id}/json`)

            .then(res => res.json())

            .then(data => {

                document.getElementById('ver_nombre').textContent = data.nombre;

                document.getElementById('ver_contacto').textContent = data.contacto_nombre ?? '—';

                document.getElementById('ver_telefono').textContent = data.contacto_telefono ?? '—';

                document.getElementById('ver_email').textContent = data.email ?? '—';

                document.getElementById('ver_direccion').textContent = data.direccion ?? '—';

                const estado = document.getElementById('ver_estado');

                estado.textContent = data.activo ? 'Activo' : 'Inactivo';

                estado.className = 'badge ' + (data.activo ? 'bg-success' : 'bg-danger');

                const diasVencimiento = data.dias_vencimiento?.dias_vencimiento ?? '—';

                document.getElementById('ver_dias_vencimiento').textContent = diasVencimiento + (diasVencimiento !== '—' ? ' días' : '');

                const tbody = document.getElementById('tabla_formas_pago');

                tbody.innerHTML = '';

                if (data.formas_pago && data.formas_pago.length > 0) {

                    data.formas_pago.forEach(fp => {

                        const row = `

                            <tr style="border-bottom: 1px solid rgba(230, 57, 70, 0.1);">

                                <td style="padding: 0.6rem; color: #90ee90; font-size: 0.85rem;">${fp.forma_pago?.nombre ?? '—'}</td>

                                <td style="padding: 0.6rem; color: #f0f0f0; font-size: 0.85rem;">${fp.banco?.nombre ?? '—'}</td>

                                <td style="padding: 0.6rem; color: #f0f0f0; font-size: 0.85rem; word-break: break-all;">${fp.referencia ?? '—'}</td>

                                <td style="padding: 0.6rem; color: #f0f0f0; font-size: 0.85rem;">${fp.nombre_banco ?? '—'}</td>

                            </tr>

                        `;

                        tbody.innerHTML += row;

                    });

                } else {

                    tbody.innerHTML = '<tr><td colspan="4" style="padding: 1rem; text-align: center; color: #a0a0a0;">No hay formas de pago registradas</td></tr>';

                }

                const modal = new bootstrap.Modal(document.getElementById('modalVerProveedor'));

                modal.show();

            })

            .catch(error => {

                console.error('Error:', error);

                alert('Error al cargar los datos');

            });

    }

    // ============ EDITAR PROVEEDOR ============

    function editarProveedor(id) {

        fetch(`/proveedores/${id}/json`)

            .then(res => res.json())

            .then(data => {

                // ✅ RELLENAR DATOS GENERALES

                document.getElementById('edit_id').value = data.id;

                document.getElementById('edit_nombre').value = data.nombre;

                document.getElementById('edit_ruc').value = data.ruc ?? '';

                document.getElementById('edit_email').value = data.email ?? '';

                document.getElementById('edit_telefono').value = data.telefono ?? '';

                document.getElementById('edit_direccion').value = data.direccion ?? '';

                document.getElementById('edit_contacto_nombre').value = data.contacto_nombre ?? '';

                document.getElementById('edit_contacto_telefono').value = data.contacto_telefono ?? '';

                document.getElementById('edit_dias_vencimiento').value = data.dias_vencimiento?.dias_vencimiento ?? 30;

                document.getElementById('edit_activo').value = data.activo ? '1' : '0';

                // ✅ CARGAR FORMAS DE PAGO

                const contenedor = document.getElementById('contenedorFormasEditarr');

                contenedor.innerHTML = '';

                contadorFormasEditar = 0;

                if (data.formas_pago && data.formas_pago.length > 0) {

                    data.formas_pago.forEach(fp => {

                        contadorFormasEditar++;

                        const index = contadorFormasEditar;

                        const html = `

                            <div class="forma-pago-editarr" id="formaEditarr_${index}" style="padding: 0.75rem; background: rgba(20, 20, 25, 0.5); border: 1px solid rgba(230, 57, 70, 0.15); border-radius: 6px; margin-bottom: 0.5rem; position: relative;">

                                <button type="button" class="btn btn-sm btn-danger" style="position: absolute; top: 0.25rem; right: 0.25rem; padding: 0.25rem 0.35rem; font-size: 0.7rem;" onclick="eliminarFormaEditarr(${index})">✕</button>

                                <p style="color: #e63946; font-size: 0.75rem; font-weight: 700; margin: 0 0 0.5rem 0; text-transform: uppercase;">Forma de Pago #${index}</p>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem; margin-bottom: 0.4rem;">

                                    <div>

                                        <label class="form-label fw-bold" style="font-size: 0.7rem; color: #e63946; margin-bottom: 0.1rem;">Tipo Pago *</label>

                                        <select name="forma_pago_id[]" class="form-select form-select-sm" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 28px; font-size: 0.75rem;">

                                            <option value="">Selecciona...</option>

                                            ${formasPagoOptions.map(fpOpt => `<option value="${fpOpt.id}" ${fpOpt.id === fp.forma_pago_id ? 'selected' : ''}>${fpOpt.nombre}</option>`).join('')}

                                        </select>

                                    </div>

                                    <div>

                                        <label class="form-label fw-bold" style="font-size: 0.7rem; color: #e63946; margin-bottom: 0.1rem;">Banco</label>

                                        <select name="banco_id[]" class="form-select form-select-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 28px; font-size: 0.75rem;">

                                            <option value="">Selecciona...</option>

                                            ${bancosOptions.map(bOpt => `<option value="${bOpt.id}" ${bOpt.id === fp.banco_id ? 'selected' : ''}>${bOpt.nombre}</option>`).join('')}

                                        </select>

                                    </div>

                                </div>

                                <div style="margin-bottom: 0.4rem;">

                                    <label class="form-label fw-bold" style="font-size: 0.7rem; color: #e63946; margin-bottom: 0.1rem;">Referencia</label>

                                    <input type="text" name="referencia[]" class="form-control form-control-sm" value="${fp.referencia ?? ''}" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 28px; font-size: 0.75rem;" placeholder="ES91 1234 5678...">

                                </div>

                                <div>

                                    <label class="form-label fw-bold" style="font-size: 0.7rem; color: #e63946; margin-bottom: 0.1rem;">Descripción</label>

                                    <input type="text" name="nombre_banco[]" class="form-control form-control-sm" value="${fp.nombre_banco ?? ''}" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 28px; font-size: 0.75rem;" placeholder="Ej: Cuenta corriente principal">

                                </div>

                            </div>

                        `;

                        contenedor.insertAdjacentHTML('beforeend', html);

                    });

                } else {

                    // Si no hay formas, agregar una en blanco

                    agregarFormaEditarr();

                }

                const modal = new bootstrap.Modal(document.getElementById('modalEditarProveedor'));

                modal.show();

            })

            .catch(error => {

                console.error('Error:', error);

                alert('Error al cargar los datos del proveedor');

            });

    }

    // ============ ENVÍO EDICIÓN ============

    const formEditar = document.getElementById('formEditarProveedor');

    if (formEditar) {

        formEditar.addEventListener('submit', function(e) {

            e.preventDefault();

            const id = document.getElementById('edit_id').value;

            const formData = new FormData(this);

            const data = {};

            formData.forEach((value, key) => {

                if (key !== '_method' && key !== '_token') {

                    if (key === 'forma_pago_id[]' || key === 'banco_id[]' || key === 'referencia[]' || key === 'nombre_banco[]') {

                        if (!data[key.replace('[]', '')]) {

                            data[key.replace('[]', '')] = [];

                        }

                        if (value !== '') {

                            data[key.replace('[]', '')].push(value);

                        }

                    } else {

                        data[key] = value;

                    }

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

                    alert('✅ ' + respuesta.message);

                    window.location.reload();

                } else {

                    alert('❌ Error: ' + (respuesta.message || 'Error desconocido'));

                }

            })

            .catch(err => {

                console.error('Error:', err);

                alert('❌ Error en la solicitud');

            });

        });

    }

    // ============ BUSCADOR + CONTADOR ============

    const buscador = document.getElementById('buscador');

    const filas = document.querySelectorAll('#tabla-proveedores tr');

    const contador = document.getElementById('contador');

    const total = filas.length;

    if (buscador) {

        buscador.addEventListener('keyup', function () {

            const filtro = this.value.toLowerCase();

            let visibles = 0;

            filas.forEach(fila => {

                const nombre = fila.querySelector('.nombre')?.textContent.toLowerCase() || '';

                if (nombre.includes(filtro)) {

                    fila.style.display = '';

                    visibles++;

                } else {

                    fila.style.display = 'none';

                }

            });

            contador.innerHTML = `<strong>Mostrando ${visibles} de ${total} proveedores</strong>`;

        });

    }

</script>

@endsection
