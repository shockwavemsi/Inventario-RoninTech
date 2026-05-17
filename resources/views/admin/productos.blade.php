@extends('layouts.app')

@section('title', 'Productos')

@section('content')

<!-- HEADER PERSONALIZADO -->
<div style="background: linear-gradient(to right, #0d0d0e 0%, #111111 100%); border-bottom: 2px solid #e63946; padding: 1.5rem 0; margin: -30px -30px 30px -30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 30px; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 50px; height: 50px; background-color: #e63946; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 28px; color: white;">📦</div>
            <div>
                <h1 style="font-size: 1.8rem; font-weight: bold; color: #f0f0f0; margin: 0;">GESTIÓN DE PRODUCTOS</h1>
                <p style="font-size: 0.75rem; color: #a0a0a0; margin: 0;">{{ $config->nombre_empresa ?? 'RoninTech' }} - Inventario</p>
            </div>
        </div>
    </div>
</div>

<!-- BARRA DE ACCIONES -->
<div style="background-color: rgba(20, 20, 25, 0.65); border-bottom: 2px solid #e63946; padding: 1.5rem 0; margin: -30px -30px 30px -30px; backdrop-filter: blur(20px);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <button class="btn btn-danger" style="font-size: 0.85rem; padding: 0.5rem 1rem;" data-bs-toggle="modal" data-bs-target="#modalProducto">
            <i class="bi bi-plus-lg"></i> Crear Nuevo Producto
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

    <!-- TABLA DE PRODUCTOS -->
    <div style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);">
        <div class="table-responsive">
            <table class="table table-dark table-hover" style="margin-bottom: 0; --bs-table-border-color: rgba(230, 57, 70, 0.1);">
                <thead style="background: rgba(230, 57, 70, 0.15);">
                    <tr>
                        <th style="color: #e63946; font-weight: 700; font-size: 0.85rem; padding: 1rem 0.75rem; text-transform: uppercase;"><i class="bi bi-hash"></i> ID</th>
                        <th style="color: #e63946; font-weight: 700; font-size: 0.85rem; padding: 1rem 0.75rem; text-transform: uppercase;"><i class="bi bi-box"></i> Nombre</th>
                        <th style="color: #e63946; font-weight: 700; font-size: 0.85rem; padding: 1rem 0.75rem; text-transform: uppercase;"><i class="bi bi-folder"></i> Categoría</th>
                        <th style="color: #e63946; font-weight: 700; font-size: 0.85rem; padding: 1rem 0.75rem; text-transform: uppercase;"><i class="bi bi-shop"></i> Proveedor</th>
                        <th style="color: #e63946; font-weight: 700; font-size: 0.85rem; padding: 1rem 0.75rem; text-transform: uppercase; text-align: right;"><i class="bi bi-cash"></i> Compra</th>
                        <th style="color: #e63946; font-weight: 700; font-size: 0.85rem; padding: 1rem 0.75rem; text-transform: uppercase; text-align: right;"><i class="bi bi-cash-coin"></i> Venta</th>
                        <th style="color: #e63946; font-weight: 700; font-size: 0.85rem; padding: 1rem 0.75rem; text-transform: uppercase;"><i class="bi bi-info-circle"></i> Estado</th>
                        <th style="color: #e63946; font-weight: 700; font-size: 0.85rem; padding: 1rem 0.75rem; text-transform: uppercase; text-align: center;"><i class="bi bi-gear"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-productos" style="color: #f0f0f0;">
                    @foreach($productos as $prod)
                        <tr data-estado="{{ $prod->activo ? 'activo' : 'inactivo' }}" data-nombre="{{ $prod->nombre }}" style="border-bottom: 1px solid rgba(230, 57, 70, 0.1); transition: background 0.2s;">
                            <td style="padding: 1rem 0.75rem; color: #a0a0a0; font-size: 0.9rem; font-weight: 600;">{{ $prod->id }}</td>
                            <td style="padding: 1rem 0.75rem; font-size: 0.9rem;" class="nombre"><i class="bi bi-box-seam" style="color: #e63946; margin-right: 0.5rem;"></i>{{ $prod->nombre }}</td>
                            <td style="padding: 1rem 0.75rem; font-size: 0.9rem; color: #b0b0b0;">{{ $prod->categoria->nombre ?? '—' }}</td>
                            <td style="padding: 1rem 0.75rem; font-size: 0.9rem; color: #b0b0b0;" class="proveedor">{{ $prod->proveedor->nombre ?? '—' }}</td>
                            <td style="padding: 1rem 0.75rem; font-size: 0.9rem; text-align: right; color: #90ee90; font-weight: 600;">${{ number_format($prod->precio_compra_final ?? 0, 2) }}</td>
                            <td style="padding: 1rem 0.75rem; font-size: 0.9rem; text-align: right; color: #90ee90; font-weight: 600;">${{ number_format($prod->precio_venta_final ?? 0, 2) }}</td>
                            <td style="padding: 1rem 0.75rem; font-size: 0.9rem;">
                                <span class="badge px-3 py-2" style="font-size: 0.75rem; font-weight: 700; @if($prod->activo) background: #4caf50; color: #c8e6c9; @else background: #666; color: #aaa; @endif">
                                    <i class="bi @if($prod->activo) bi-check-circle-fill @else bi-x-circle @endif me-1"></i>{{ $prod->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td style="padding: 1rem 0.75rem; text-align: center;">
                                <select class="form-select form-select-sm accion-producto" data-id="{{ $prod->id }}" style="background: rgba(100, 100, 100, 0.2); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.8rem; padding: 0.4rem 0.6rem;">
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

<!-- ============ MODAL CREAR PRODUCTO ============ -->
<div class="modal fade" id="modalProducto" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3); border-radius: 8px;">
            <form action="{{ route('productos.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="background: rgba(230, 57, 70, 0.15); border-bottom: 2px solid #e63946; padding: 0.75rem 1rem;">
                    <h5 class="modal-title fw-bold" style="color: #e63946; margin: 0; font-size: 1.1rem;">➕ Crear Nuevo Producto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="color: #f0f0f0; padding: 1rem; max-height: 70vh; overflow-y: auto;">
                    <!-- ROW 1: NOMBRE, MARCA, MODELO (3 COL) -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.4rem; margin-bottom: 0.5rem;">
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Nombre *</label>
                            <input type="text" name="nombre" class="form-control form-control-sm" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                        </div>
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Marca</label>
                            <input type="text" name="marca" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                        </div>
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Modelo</label>
                            <input type="text" name="modelo" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                        </div>
                    </div>

                    <!-- ROW 2: CATEGORÍA, PROVEEDOR, UBICACIÓN (3 COL) -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.4rem; margin-bottom: 0.5rem;">
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Categoría *</label>
                            <select name="categoria_id" class="form-select form-select-sm" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                                <option value="">Elige...</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Proveedor *</label>
                            <select name="proveedor_id" class="form-select form-select-sm" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                                <option value="">Elige...</option>
                                @foreach($proveedores as $prov)
                                    <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Ubicación</label>
                            <input type="text" name="ubicacion" class="form-control form-control-sm" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                        </div>
                    </div>

                    <!-- DESCRIPCIÓN -->
                    <div style="margin-bottom: 0.5rem;">
                        <label class="form-label fw-bold" style="font-size: 0.8rem; color: #e63946; margin-bottom: 0.25rem;">Descripción</label>
                        <textarea name="descripcion" class="form-control form-control-sm" rows="2" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.85rem;"></textarea>
                    </div>

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 0.75rem 0;">

                    <!-- SECCIÓN COMPRA -->
                    <div style="display: grid; grid-template-columns: auto 1fr; gap: 0.5rem; align-items: center; margin-bottom: 0.75rem; padding: 0.6rem; background: rgba(230, 57, 70, 0.08); border-radius: 6px; border-left: 3px solid #e63946;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                        <h6 style="color: #e63946; font-weight: 700; font-size: 0.8rem; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">💳 Compra</h6>
                    </div>

                    <div style="display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 0.4rem; margin-bottom: 0.5rem;">
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Base Compra *</label>
                            <input type="number" name="precio_base_compra" id="precioBaseCompra" class="form-control form-control-sm" step="0.01" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                        </div>
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">IVA *</label>
                            <select name="iva_compra_id" id="ivaCompra" class="form-select form-select-sm" required onchange="calcularPrecios()" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                                <option value="">IVA</option>
                                @foreach($ivas ?? [] as $iva)
                                    <option value="{{ $iva->id }}" data-porcentaje="{{ $iva->porcentaje }}">{{ $iva->porcentaje }}%</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="display: flex; flex-direction: column; justify-content: flex-end;">
                            <label class="form-label fw-bold" style="font-size: 0.7rem; color: #90ee90; margin-bottom: 0.15rem;">Total</label>
                            <div style="background: rgba(76, 175, 80, 0.12); padding: 0.5rem 0.6rem; border-radius: 4px; border-left: 2px solid #4caf50;">
                                <strong id="precioCompraFinal" style="color: #90ee90; font-size: 0.85rem;">$0.00</strong>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="precio_compra_final" id="precioCompraFinalInput" value="0">

                    <!-- SEPARADOR -->
                    <div style="text-align: center; margin: 0.5rem 0; color: #666;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2" style="margin: 0 auto; opacity: 0.5;"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg>
                    </div>

                    <!-- SECCIÓN VENTA -->
                    <div style="display: grid; grid-template-columns: auto 1fr; gap: 0.5rem; align-items: center; margin-bottom: 0.75rem; padding: 0.6rem; background: rgba(230, 57, 70, 0.08); border-radius: 6px; border-left: 3px solid #e63946;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><line x1="12" y1="2" x2="12" y2="22"></line><path d="M17 5H9.5a1.5 1.5 0 0 0-1.5 1.5v12a1.5 1.5 0 0 0 1.5 1.5H17"></path><path d="M7 12l4-4 4 4"></path></svg>
                        <h6 style="color: #e63946; font-weight: 700; font-size: 0.8rem; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">💰 Venta</h6>
                    </div>

                    <div style="display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 0.4rem; margin-bottom: 0.5rem;">
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Base Venta *</label>
                            <input type="number" name="precio_base_venta" id="precioBaseVenta" class="form-control form-control-sm" step="0.01" required oninput="calcularPrecios()" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                        </div>
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">IVA *</label>
                            <select name="iva_venta_id" id="ivaVenta" class="form-select form-select-sm" required onchange="calcularPrecios()" style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                                <option value="">IVA</option>
                                @foreach($ivas ?? [] as $iva)
                                    <option value="{{ $iva->id }}" data-porcentaje="{{ $iva->porcentaje }}">{{ $iva->porcentaje }}%</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="display: flex; flex-direction: column; justify-content: flex-end;">
                            <label class="form-label fw-bold" style="font-size: 0.7rem; color: #90ee90; margin-bottom: 0.15rem;">Total</label>
                            <div style="background: rgba(76, 175, 80, 0.12); padding: 0.5rem 0.6rem; border-radius: 4px; border-left: 2px solid #4caf50;">
                                <strong id="precioVentaFinal" style="color: #90ee90; font-size: 0.85rem;">$0.00</strong>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="precio_venta_final" id="precioVentaFinalInput" value="0">

                    <hr style="border-color: rgba(230, 57, 70, 0.3); margin: 0.75rem 0;">
                    <h6 style="color: #e63946; font-weight: 700; font-size: 0.8rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">📊 Stock</h6>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem;">
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Mínimo *</label>
                            <input type="number" name="stock_minimo" class="form-control form-control-sm" value="3" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                        </div>
                        <div>
                            <label class="form-label fw-bold" style="font-size: 0.75rem; color: #e63946; margin-bottom: 0.15rem;">Máximo *</label>
                            <input type="number" name="stock_maximo" class="form-control form-control-sm" value="100" required style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; height: 30px; font-size: 0.8rem;">
                        </div>
                    </div>
                    <p style="font-size: 0.7rem; color: #a0a0a0; margin-top: 0.4rem; margin-bottom: 0;"><strong>ℹ️ Nota:</strong> Stock Actual se genera auto (aleatorio).</p>

                    <input type="hidden" name="activo" value="1">
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3); padding: 0.75rem 1rem;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger btn-sm">💾 Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============ MODAL VER PRODUCTO (MODERNO Y REACTIVO) ============ -->
<div class="modal fade" id="modalVerProducto" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3); border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(to right, rgba(230, 57, 70, 0.2), rgba(76, 175, 80, 0.1)); border-bottom: 2px solid #e63946; padding: 1rem;">
                <div>
                    <h5 class="modal-title fw-bold" style="color: #e63946; margin: 0; font-size: 1.2rem;">📦 Detalles del Producto</h5>
                    <p style="color: #a0a0a0; font-size: 0.75rem; margin: 0.3rem 0 0 0; text-transform: uppercase; letter-spacing: 0.5px;">Información Completa</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="color: #f0f0f0; padding: 1.25rem; max-height: 75vh; overflow-y: auto;">
                <!-- ENCABEZADO PRODUCTO -->
                <div style="padding: 1rem; background: rgba(230, 57, 70, 0.08); border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 8px; margin-bottom: 1.25rem;">
                    <h4 style="color: #f0f0f0; margin: 0 0 0.5rem 0; font-weight: 700; font-size: 1.3rem;" id="ver_nombre">—</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                        <div>
                            <p style="color: #a0a0a0; font-size: 0.7rem; margin: 0; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Marca</p>
                            <p style="color: #e63946; font-weight: 600; font-size: 1rem; margin: 0.2rem 0 0 0;" id="ver_marca">—</p>
                        </div>
                        <div>
                            <p style="color: #a0a0a0; font-size: 0.7rem; margin: 0; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Modelo</p>
                            <p style="color: #e63946; font-weight: 600; font-size: 1rem; margin: 0.2rem 0 0 0;" id="ver_modelo">—</p>
                        </div>
                        <div>
                            <p style="color: #a0a0a0; font-size: 0.7rem; margin: 0; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Categoría</p>
                            <p style="color: #e63946; font-weight: 600; font-size: 1rem; margin: 0.2rem 0 0 0;" id="ver_categoria">—</p>
                        </div>
                    </div>
                </div>

                <!-- INFO GENERAL -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                    <div style="padding: 0.8rem; background: rgba(100, 100, 100, 0.1); border-left: 3px solid #e63946; border-radius: 6px;">
                        <p style="color: #a0a0a0; font-size: 0.7rem; margin: 0 0 0.3rem 0; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Proveedor</p>
                        <p style="color: #f0f0f0; font-size: 0.95rem; font-weight: 500; margin: 0;" id="ver_proveedor">—</p>
                    </div>
                    <div style="padding: 0.8rem; background: rgba(100, 100, 100, 0.1); border-left: 3px solid #ff9800; border-radius: 6px;">
                        <p style="color: #a0a0a0; font-size: 0.7rem; margin: 0 0 0.3rem 0; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Ubicación</p>
                        <p style="color: #ffb74d; font-size: 0.95rem; font-weight: 500; margin: 0;" id="ver_ubicacion">—</p>
                    </div>
                </div>

                <!-- DESCRIPCIÓN -->
                <div style="padding: 0.8rem; background: rgba(230, 57, 70, 0.05); border-left: 3px solid #e63946; border-radius: 6px; margin-bottom: 1.25rem;">
                    <p style="color: #a0a0a0; font-size: 0.7rem; margin: 0 0 0.3rem 0; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Descripción</p>
                    <p style="color: #d0d0d0; font-size: 0.9rem; margin: 0; line-height: 1.4;" id="ver_descripcion">—</p>
                </div>

                <!-- SECCIÓN COMPRA -->
                <div style="background: rgba(230, 57, 70, 0.08); border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 8px; padding: 1rem; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                        <h6 style="color: #e63946; font-weight: 700; font-size: 0.95rem; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">Compra</h6>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem;">
                        <div style="padding: 0.7rem; background: rgba(20, 20, 25, 0.4); border-radius: 6px;">
                            <p style="color: #a0a0a0; font-size: 0.7rem; margin: 0 0 0.25rem 0; text-transform: uppercase; font-weight: 600;">Precio Base</p>
                            <p style="color: #90ee90; font-weight: 600; font-size: 1rem; margin: 0;">$<span id="ver_precio_base_compra">0.00</span></p>
                        </div>
                        <div style="padding: 0.7rem; background: rgba(20, 20, 25, 0.4); border-radius: 6px;">
                            <p style="color: #a0a0a0; font-size: 0.7rem; margin: 0 0 0.25rem 0; text-transform: uppercase; font-weight: 600;">IVA</p>
                            <p style="color: #90ee90; font-weight: 600; font-size: 1rem; margin: 0;"><span id="ver_iva_compra">0</span>%</p>
                        </div>
                        <div style="padding: 0.7rem; background: rgba(76, 175, 80, 0.15); border: 1px solid #4caf50; border-radius: 6px;">
                            <p style="color: #a0a0a0; font-size: 0.7rem; margin: 0 0 0.25rem 0; text-transform: uppercase; font-weight: 600; color: #4caf50;">Final</p>
                            <p style="color: #4caf50; font-weight: 700; font-size: 1.1rem; margin: 0;">$<span id="ver_precio_compra_final">0.00</span></p>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN VENTA -->
                <div style="background: rgba(76, 175, 80, 0.08); border: 1px solid rgba(76, 175, 80, 0.2); border-radius: 8px; padding: 1rem; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#4caf50" stroke-width="2"><line x1="12" y1="2" x2="12" y2="22"></line><path d="M17 5H9.5a1.5 1.5 0 0 0-1.5 1.5v12a1.5 1.5 0 0 0 1.5 1.5H17"></path><path d="M7 12l4-4 4 4"></path></svg>
                        <h6 style="color: #4caf50; font-weight: 700; font-size: 0.95rem; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">Venta</h6>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem;">
                        <div style="padding: 0.7rem; background: rgba(20, 20, 25, 0.4); border-radius: 6px;">
                            <p style="color: #a0a0a0; font-size: 0.7rem; margin: 0 0 0.25rem 0; text-transform: uppercase; font-weight: 600;">Precio Base</p>
                            <p style="color: #90ee90; font-weight: 600; font-size: 1rem; margin: 0;">$<span id="ver_precio_base_venta">0.00</span></p>
                        </div>
                        <div style="padding: 0.7rem; background: rgba(20, 20, 25, 0.4); border-radius: 6px;">
                            <p style="color: #a0a0a0; font-size: 0.7rem; margin: 0 0 0.25rem 0; text-transform: uppercase; font-weight: 600;">IVA</p>
                            <p style="color: #90ee90; font-weight: 600; font-size: 1rem; margin: 0;"><span id="ver_iva_venta">0</span>%</p>
                        </div>
                        <div style="padding: 0.7rem; background: rgba(76, 175, 80, 0.15); border: 1px solid #4caf50; border-radius: 6px;">
                            <p style="color: #a0a0a0; font-size: 0.7rem; margin: 0 0 0.25rem 0; text-transform: uppercase; font-weight: 600; color: #4caf50;">Final</p>
                            <p style="color: #4caf50; font-weight: 700; font-size: 1.1rem; margin: 0;">$<span id="ver_precio_venta_final">0.00</span></p>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN STOCK -->
                <div style="background: rgba(255, 152, 0, 0.08); border: 1px solid rgba(255, 152, 0, 0.2); border-radius: 8px; padding: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ff9800" stroke-width="2"><path d="M6 9l12 0"></path><path d="M6 20h12a2 2 0 0 0 2 -2v-11a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v11a2 2 0 0 0 2 2"></path><path d="M9 5v-2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v2"></path></svg>
                        <h6 style="color: #ff9800; font-weight: 700; font-size: 0.95rem; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">Stock</h6>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem;">
                        <div style="padding: 0.7rem; background: rgba(20, 20, 25, 0.4); border-radius: 6px;">
                            <p style="color: #a0a0a0; font-size: 0.7rem; margin: 0 0 0.25rem 0; text-transform: uppercase; font-weight: 600;">Actual</p>
                            <p style="color: #ffb74d; font-weight: 700; font-size: 1.15rem; margin: 0;" id="ver_stock_actual">0</p>
                        </div>
                        <div style="padding: 0.7rem; background: rgba(20, 20, 25, 0.4); border-radius: 6px;">
                            <p style="color: #a0a0a0; font-size: 0.7rem; margin: 0 0 0.25rem 0; text-transform: uppercase; font-weight: 600;">Mínimo</p>
                            <p style="color: #f08080; font-weight: 600; font-size: 1rem; margin: 0;" id="ver_stock_minimo">3</p>
                        </div>
                        <div style="padding: 0.7rem; background: rgba(20, 20, 25, 0.4); border-radius: 6px;">
                            <p style="color: #a0a0a0; font-size: 0.7rem; margin: 0 0 0.25rem 0; text-transform: uppercase; font-weight: 600;">Máximo</p>
                            <p style="color: #ffb74d; font-weight: 600; font-size: 1rem; margin: 0;" id="ver_stock_maximo">100</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3); padding: 1rem; background: rgba(20, 20, 25, 0.5);">
                <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal">✕ Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // ACCIONES EN LA TABLA
    document.querySelectorAll('.accion-producto').forEach(select => {
        select.addEventListener('change', function () {
            const id = this.dataset.id;
            const accion = this.value;
            
            if (accion === 'ver') {
                mostrarProducto(id);
            }
            
            if (accion === 'editar') {
                window.location.href = `/productos/${id}/editar`;
            }
            
            if (accion === 'eliminar') {
                if (confirm('¿Seguro que deseas eliminar este producto?')) {
                    fetch(`/productos/${id}/eliminar`, {
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

    // MOSTRAR PRODUCTO (CON TODOS LOS DATOS)
    function mostrarProducto(id) {
        fetch(`/productos/${id}/json`)
            .then(res => res.json())
            .then(data => {
                // INFORMACIÓN GENERAL
                document.getElementById('ver_nombre').textContent = data.nombre ?? '—';
                document.getElementById('ver_marca').textContent = data.marca ?? '—';
                document.getElementById('ver_modelo').textContent = data.modelo ?? '—';
                document.getElementById('ver_categoria').textContent = data.categoria?.nombre ?? '—';
                document.getElementById('ver_proveedor').textContent = data.proveedor?.nombre ?? '—';
                document.getElementById('ver_descripcion').textContent = data.descripcion ?? '—';
                document.getElementById('ver_ubicacion').textContent = data.ubicacion ?? '—';
                
                // PRECIOS COMPRA
                document.getElementById('ver_precio_base_compra').textContent = (parseFloat(data.precio_base_compra) || 0).toFixed(2);
                document.getElementById('ver_iva_compra').textContent = (parseFloat(data.iva_compra?.porcentaje) || 0).toFixed(2);
                document.getElementById('ver_precio_compra_final').textContent = (parseFloat(data.precio_compra_final) || 0).toFixed(2);
                
                // PRECIOS VENTA
                document.getElementById('ver_precio_base_venta').textContent = (parseFloat(data.precio_base_venta) || 0).toFixed(2);
                document.getElementById('ver_iva_venta').textContent = (parseFloat(data.iva_venta?.porcentaje) || 0).toFixed(2);
                document.getElementById('ver_precio_venta_final').textContent = (parseFloat(data.precio_venta_final) || 0).toFixed(2);
                
                // STOCK
                document.getElementById('ver_stock_actual').textContent = data.stock_actual ?? '0';
                document.getElementById('ver_stock_minimo').textContent = data.stock_minimo ?? '3';
                document.getElementById('ver_stock_maximo').textContent = data.stock_maximo ?? '100';
                
                const modal = new bootstrap.Modal(document.getElementById('modalVerProducto'));
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los datos del producto');
            });
    }

    // BUSCADOR
    const buscador = document.getElementById('buscador');
    const filas = document.querySelectorAll('#tabla-productos tr');

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

    // FUNCIÓN PARA CALCULAR PRECIOS AUTOMÁTICAMENTE
    function calcularPrecios() {
        // COMPRA
        const precioBaseCompra = parseFloat(document.getElementById('precioBaseCompra').value) || 0;
        const selectIvaCompra = document.getElementById('ivaCompra');
        const porcentajeIvaCompra = selectIvaCompra.options[selectIvaCompra.selectedIndex].getAttribute('data-porcentaje') || 0;
        const precioCompraFinal = precioBaseCompra * (1 + (porcentajeIvaCompra / 100));
        
        document.getElementById('precioCompraFinal').textContent = '$' + precioCompraFinal.toFixed(2);
        document.getElementById('precioCompraFinalInput').value = precioCompraFinal.toFixed(2);

        // REFLEJO AUTOMÁTICO: precio_compra_final → precio_base_venta
        document.getElementById('precioBaseVenta').value = precioCompraFinal.toFixed(2);

        // VENTA
        const precioBaseVenta = precioCompraFinal;
        const selectIvaVenta = document.getElementById('ivaVenta');
        const porcentajeIvaVenta = selectIvaVenta.options[selectIvaVenta.selectedIndex].getAttribute('data-porcentaje') || 0;
        const precioVentaFinal = precioBaseVenta * (1 + (porcentajeIvaVenta / 100));
        
        document.getElementById('precioVentaFinal').textContent = '$' + precioVentaFinal.toFixed(2);
        document.getElementById('precioVentaFinalInput').value = precioVentaFinal.toFixed(2);
    }
</script>

@endsection
