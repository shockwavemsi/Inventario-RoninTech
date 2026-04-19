// public/js/compras/compras-ver.js

function mostrarCompra(id) {
    console.log('Cargando compra ID:', id);
    
    const tbody = document.getElementById('detalles-compra');
    if (!tbody) return;
    
    tbody.innerHTML = '<td><td colspan="7" class="text-center"><div class="spinner-border spinner-border-sm"></div> Cargando...</td><tr>';
    
    // ✅ CORREGIDO: Usar la ruta correcta /json
    fetch(`/compras/${id}/json`)
        .then(res => {
            if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
            return res.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            
            // Información general
            if (document.getElementById('ver_codigo')) 
                document.getElementById('ver_codigo').textContent = data.numero_factura || '—';
            if (document.getElementById('ver_proveedor')) 
                document.getElementById('ver_proveedor').textContent = data.proveedor?.nombre ?? '—';
            if (document.getElementById('ver_fecha_pedido')) 
                document.getElementById('ver_fecha_pedido').textContent = data.fecha_pedido ? new Date(data.fecha_pedido).toLocaleDateString('es-ES') : '—';
            if (document.getElementById('ver_fecha_entrega')) 
                document.getElementById('ver_fecha_entrega').textContent = data.fecha_entrega_esperada ? new Date(data.fecha_entrega_esperada).toLocaleDateString('es-ES') : 'No especificada';
            if (document.getElementById('ver_usuario')) 
                document.getElementById('ver_usuario').textContent = data.usuario?.name ?? '—';
            if (document.getElementById('ver_created_at')) 
                document.getElementById('ver_created_at').textContent = data.created_at ? new Date(data.created_at).toLocaleDateString('es-ES') : '—';
            if (document.getElementById('ver_observaciones')) 
                document.getElementById('ver_observaciones').textContent = data.observaciones || 'Sin observaciones';
            
            // Estado
            const estadoSpan = document.getElementById('ver_estado');
            if (estadoSpan) {
                estadoSpan.textContent = data.estado ? data.estado.charAt(0).toUpperCase() + data.estado.slice(1) : 'Desconocido';
                estadoSpan.className = 'badge ' + (data.estado === 'pendiente' ? 'bg-warning' : data.estado === 'recibido' ? 'bg-success' : 'bg-secondary');
            }
            
            // Totales
            if (document.getElementById('ver_subtotal')) 
                document.getElementById('ver_subtotal').textContent = '$' + (parseFloat(data.subtotal) || 0).toFixed(2);
            if (document.getElementById('ver_impuesto')) 
                document.getElementById('ver_impuesto').textContent = '$' + (parseFloat(data.impuesto) || 0).toFixed(2);
            if (document.getElementById('ver_total')) 
                document.getElementById('ver_total').textContent = '$' + (parseFloat(data.total) || 0).toFixed(2);
            if (document.getElementById('ver_descuento')) 
                document.getElementById('ver_descuento').textContent = '$' + (parseFloat(data.descuento) || 0).toFixed(2);
            
            // Productos
            tbody.innerHTML = '';
            if (data.detalles && data.detalles.length > 0) {
                data.detalles.forEach((det, index) => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td><strong>${det.producto?.nombre ?? 'Producto #' + det.producto_id}</strong><br><small>Código: ${det.producto?.id || 'N/A'}</small></td>
                            <td>${det.producto?.marca ?? '—'} / ${det.producto?.modelo ?? '—'}</td>
                            <td class="text-center">${det.cantidad || 0}</td>
                            <td class="text-end">$${(parseFloat(det.precio_unitario) || 0).toFixed(2)}</td>
                            <td class="text-end">$${(parseFloat(det.descuento) || 0).toFixed(2)}</td>
                            <td class="text-end">$${(parseFloat(det.subtotal) || 0).toFixed(2)}</td>
                        </tr>
                    `;
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No hay productos registrados</td><tr>';
            }
            
            new bootstrap.Modal(document.getElementById('modalVerCompra')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            tbody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error: ${error.message}</td><tr>`;
            alert('Error al cargar los detalles de la compra: ' + error.message);
        });
}

// Inicializar eventos de ver
function initAccionesCompra() {
    document.querySelectorAll('.ver-compra').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            console.log('Botón Ver clickeado, ID:', id);
            mostrarCompra(id);
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initAccionesCompra();
});