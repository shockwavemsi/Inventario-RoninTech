@extends('layouts.app')

@section('title', 'Clientes')

@section('content')

<div style="background: linear-gradient(to right, #0d0d0e 0%, #111111 100%); border-bottom: 2px solid #e63946; padding: 1.5rem 0; margin: -30px -30px 30px -30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 30px; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 50px; height: 50px; background-color: #e63946; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 28px; color: white;">👥</div>
            <div>
                <h1 style="font-size: 1.8rem; font-weight: bold; color: #f0f0f0; margin: 0;">GESTIÓN DE CLIENTES</h1>
                <p style="font-size: 0.75rem; color: #a0a0a0; margin: 0;">{{ $config->nombre_empresa ?? 'RoninTech' }} - Ventas</p>
            </div>
        </div>
    </div>
</div>

<div style="background-color: rgba(20, 20, 25, 0.65); border-bottom: 2px solid #e63946; padding: 1.5rem 0; margin: -30px -30px 30px -30px; backdrop-filter: blur(20px);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalCrearCliente" style="font-size: 0.85rem;">
            <i class="bi bi-plus-lg"></i> Crear Cliente
        </button>
        <form method="GET" action="{{ route('clientes.index') }}" style="display: flex; gap: 0.5rem; max-width: 440px; width: 100%;">
            <input type="text" name="buscar" value="{{ $buscar }}" class="form-control" placeholder="Buscar por nombre, documento o teléfono..." style="background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.85rem;">
            <button class="btn btn-outline-danger" type="submit">Buscar</button>
        </form>
    </div>
</div>

<div style="max-width: 1200px; margin: 0 auto;">
    @if(session('success'))
        <div class="alert alert-success" style="background: rgba(76, 175, 80, 0.15); border: 1px solid #4caf50; color: #90ee90;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.2); border-radius: 8px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-dark table-hover" style="margin-bottom: 0;">
                <thead style="background: rgba(230, 57, 70, 0.15);">
                    <tr>
                        <th style="color: #e63946;">ID</th>
                        <th style="color: #e63946;">Nombre</th>
                        <th style="color: #e63946;">Documento</th>
                        <th style="color: #e63946;">Teléfono</th>
                        <th style="color: #e63946;">Estado</th>
                        <th style="color: #e63946; text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                        <tr>
                            <td style="color: #a0a0a0;">{{ $cliente->id }}</td>
                            <td style="color: #f0f0f0;">{{ $cliente->nombre }} {{ $cliente->apellido }}</td>
                            <td style="color: #f0f0f0;">{{ $cliente->documento }}</td>
                            <td style="color: #f0f0f0;">{{ $cliente->telefono }}</td>
                            <td>
                                <span class="badge" style="background: {{ $cliente->activo ? '#4caf50' : '#666' }}; color: {{ $cliente->activo ? '#c8e6c9' : '#aaa' }};">
                                    {{ $cliente->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <select class="form-select form-select-sm accion-cliente" data-id="{{ $cliente->id }}" style="background: rgba(100, 100, 100, 0.2); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0; font-size: 0.8rem; max-width: 130px; margin: 0 auto;">
                                    <option value="">Acciones</option>
                                    <option value="ver">Ver</option>
                                    <option value="editar">Editar</option>
                                    <option value="eliminar">Eliminar</option>
                                </select>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #a0a0a0; padding: 2rem;">Sin clientes registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top: 1rem;">
        {{ $clientes->links() }}
    </div>
</div>

@php
    $inputStyle = "background: rgba(20, 20, 25, 0.8); border-color: rgba(230, 57, 70, 0.3); color: #f0f0f0;";
@endphp

<div class="modal fade" id="modalCrearCliente" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3);">
            <form method="POST" action="{{ route('clientes.store') }}">
                @csrf
                <div class="modal-header" style="border-bottom: 2px solid #e63946;">
                    <h5 class="modal-title" style="color: #e63946;">Crear Cliente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="color: #f0f0f0;">
                    <div class="row g-2">
                        <div class="col-md-6"><label class="form-label">Nombre</label><input name="nombre" class="form-control" required style="{{ $inputStyle }}"></div>
                        <div class="col-md-6"><label class="form-label">Apellido</label><input name="apellido" class="form-control" required style="{{ $inputStyle }}"></div>
                        <div class="col-md-6"><label class="form-label">Documento</label><input name="documento" class="form-control" required style="{{ $inputStyle }}"></div>
                        <div class="col-md-6"><label class="form-label">Teléfono</label><input name="telefono" class="form-control" required style="{{ $inputStyle }}"></div>
                        <input type="hidden" name="activo" value="1">
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3);">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-danger btn-sm">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalVerCliente" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3);">
            <div class="modal-header" style="border-bottom: 2px solid #e63946;">
                <h5 class="modal-title" style="color: #e63946;">Detalle Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="color: #f0f0f0;">
                <p><strong>Nombre:</strong> <span id="verClienteNombre"></span></p>
                <p><strong>Documento:</strong> <span id="verClienteDocumento"></span></p>
                <p><strong>Teléfono:</strong> <span id="verClienteTelefono"></span></p>
                <p><strong>Estado:</strong> <span id="verClienteEstado"></span></p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarCliente" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: rgba(20, 20, 25, 0.95); border: 1px solid rgba(230, 57, 70, 0.3);">
            <form id="formEditarCliente">
                @csrf
                @method('PUT')
                <div class="modal-header" style="border-bottom: 2px solid #e63946;">
                    <h5 class="modal-title" style="color: #e63946;">Editar Cliente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="color: #f0f0f0;">
                    <div class="row g-2">
                        <div class="col-md-6"><label class="form-label">Nombre</label><input name="nombre" id="editarClienteNombre" class="form-control" required style="{{ $inputStyle }}"></div>
                        <div class="col-md-6"><label class="form-label">Apellido</label><input name="apellido" id="editarClienteApellido" class="form-control" required style="{{ $inputStyle }}"></div>
                        <div class="col-md-6"><label class="form-label">Documento</label><input name="documento" id="editarClienteDocumento" class="form-control" required style="{{ $inputStyle }}"></div>
                        <div class="col-md-6"><label class="form-label">Teléfono</label><input name="telefono" id="editarClienteTelefono" class="form-control" required style="{{ $inputStyle }}"></div>
                        <div class="col-12">
                            <label class="form-check">
                                <input type="checkbox" name="activo" value="1" id="editarClienteActivo" class="form-check-input">
                                <span class="form-check-label">Activo</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(230, 57, 70, 0.3);">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-danger btn-sm">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const csrfCliente = document.querySelector('meta[name="csrf-token"]').content;

document.querySelectorAll('.accion-cliente').forEach(select => {
    select.addEventListener('change', async function () {
        const id = this.dataset.id;
        const accion = this.value;
        this.value = '';

        if (accion === 'ver') return verCliente(id);
        if (accion === 'editar') return editarCliente(id);
        if (accion === 'eliminar' && confirm('¿Eliminar este cliente?')) {
            const response = await fetch(`/clientes/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfCliente } });
            if (response.ok) window.location.reload();
            else alert('No se pudo eliminar el cliente');
        }
    });
});

async function obtenerCliente(id) {
    const response = await fetch(`/clientes/${id}/json`);
    if (!response.ok) throw new Error('No se pudo cargar el cliente');
    return response.json();
}

async function verCliente(id) {
    const cliente = await obtenerCliente(id);
    document.getElementById('verClienteNombre').textContent = cliente.nombre_completo;
    document.getElementById('verClienteDocumento').textContent = cliente.documento;
    document.getElementById('verClienteTelefono').textContent = cliente.telefono;
    document.getElementById('verClienteEstado').textContent = cliente.activo ? 'Activo' : 'Inactivo';
    new bootstrap.Modal(document.getElementById('modalVerCliente')).show();
}

async function editarCliente(id) {
    const cliente = await obtenerCliente(id);
    const form = document.getElementById('formEditarCliente');
    form.dataset.id = id;
    document.getElementById('editarClienteNombre').value = cliente.nombre;
    document.getElementById('editarClienteApellido').value = cliente.apellido;
    document.getElementById('editarClienteDocumento').value = cliente.documento;
    document.getElementById('editarClienteTelefono').value = cliente.telefono;
    document.getElementById('editarClienteActivo').checked = cliente.activo;
    new bootstrap.Modal(document.getElementById('modalEditarCliente')).show();
}

document.getElementById('formEditarCliente').addEventListener('submit', async function (event) {
    event.preventDefault();
    const id = this.dataset.id;
    const formData = new FormData(this);
    const response = await fetch(`/clientes/${id}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfCliente, 'X-HTTP-Method-Override': 'PUT', 'Accept': 'application/json' },
        body: formData,
    });

    if (response.ok) window.location.reload();
    else alert('No se pudo actualizar el cliente');
});
</script>

@endsection
