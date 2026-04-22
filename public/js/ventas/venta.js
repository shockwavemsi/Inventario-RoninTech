// Generar código de venta automáticamente
document.addEventListener('DOMContentLoaded', function() {
    generarCodigoVenta();

    document.getElementById('modalVenta').addEventListener('show.bs.modal', generarCodigoVenta);
});

function generarCodigoVenta() {
    // Contar ventas existentes y generar siguiente código
    const filas = document.querySelectorAll('#tabla-ventas tr').length;
    const nuevoNumero = filas + 1;
    const codigoFormato = 'V-' + String(nuevoNumero).padStart(3, '0'); // V-001, V-002, etc.

    document.getElementById('codigo_venta').value = codigoFormato;
    document.getElementById('numero_factura').value = codigoFormato;
}

// ACCIONES
document.querySelectorAll('.accion-venta').forEach(select => {
    select.addEventListener('change', function () {
        const id = this.dataset.id;
        const accion = this.value;

        if (accion === 'ver') {
            mostrarVenta(id);
        }
        if (accion === 'editar') {
            window.location.href = `/ventas/${id}/editar`;
        }
        if (accion === 'eliminar') {
            if (confirm('¿Seguro que deseas eliminar esta venta?')) {
                fetch(`/ventas/${id}/eliminar`, {
                    method: 'DELETE',
                    headers: { 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(res => {
                    if (res.ok) {
                        window.location.reload();
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Error al eliminar');
                });
            }
        }
        this.value = '';
    });
});

// VER VENTA
function mostrarVenta(id) {
    fetch(`/ventas/${id}/json`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('ver_codigo').textContent = data.numero_factura;
            document.getElementById('ver_cliente').textContent = data.cliente;
            document.getElementById('ver_usuario').textContent = data.usuario?.name ?? '—';
            document.getElementById('ver_fecha').textContent = new Date(data.created_at).toLocaleDateString('es-ES');
            document.getElementById('ver_total').textContent = data.total;
            document.getElementById('ver_observaciones').textContent = data.observaciones ?? '—';

            const estado = document.getElementById('ver_estado');
            estado.textContent = data.estado.charAt(0).toUpperCase() + data.estado.slice(1);
            estado.className = data.estado === 'completada' ? 'badge bg-success' : 'badge bg-warning';

            // Tabla de detalles
            const tbody = document.getElementById('ver_detalles');
            tbody.innerHTML = '';
            if (data.detalles && data.detalles.length > 0) {
                data.detalles.forEach(detalle => {
                    const row = `<tr>
                        <td>${detalle.producto.nombre ?? '—'}</td>
                        <td>${detalle.cantidad}</td>
                        <td>$${detalle.precio_unitario}</td>
                        <td>$${detalle.subtotal}</td>
                    </tr>`;
                    tbody.innerHTML += row;
                });
            }

            const modal = new bootstrap.Modal(document.getElementById('modalVerVenta'));
            modal.show();
        });
}

// BUSCADOR
const buscador = document.getElementById('buscador');
const filas = document.querySelectorAll('#tabla-ventas tr');
const contador = document.getElementById('contador');
const total = filas.length;

buscador.addEventListener('keyup', function () {
    const filtro = this.value.toLowerCase();
    let visibles = 0;
    filas.forEach(fila => {
        const cliente = fila.querySelector('.cliente')?.textContent.toLowerCase() || '';
        if (cliente.includes(filtro)) {
            fila.style.display = '';
            visibles++;
        } else {
            fila.style.display = 'none';
        }
    });
    contador.innerHTML = `<strong>Mostrando ${visibles} de ${total} ventas</strong>`;
});