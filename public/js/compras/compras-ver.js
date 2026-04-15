// public/js/compras/compras-ver.js
document.querySelectorAll('.ver-compra').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        
        // Mostrar loading
        const modalBody = document.querySelector('#modalVerCompra .modal-body');
        const originalContent = modalBody.innerHTML;
        modalBody.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div><p class="mt-3">Cargando detalles de la compra...</p></div>';
        
        // Cambia esta ruta según tu backend
        fetch(`/compras/${id}/detalles`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Restaurar contenido original y llenar datos
                location.reload(); // Temporal: recarga la página
                
                /* Código original para cuando funcione la ruta:
                document.getElementById('ver_codigo').textContent = data.numero_factura || data.codigo || 'N/A';
                document.getElementById('ver_proveedor').textContent = data.proveedor_nombre || data.proveedor?.nombre || '—';
                document.getElementById('ver_fecha_pedido').textContent = data.fecha_pedido || data.created_at || '—';
                document.getElementById('ver_fecha_entrega').textContent = data.fecha_entrega_esperada || 'No especificada';
                document.getElementById('ver_usuario').textContent = data.usuario_nombre || data.usuario?.name || '—';
                document.getElementById('ver_created_at').textContent = data.created_at || '—';
                document.getElementById('ver_observaciones').textContent = data.observaciones || 'Sin observaciones';
                
                // Estado
                const estadoSpan = document.getElementById('ver_estado');
                const estado = data.estado || 'pendiente';
                estadoSpan.textContent = estado.charAt(0).toUpperCase() + estado.slice(1);
                estadoSpan.className = `badge ${estado === 'pendiente' ? 'bg-warning text-dark' : 'bg-success'}`;
                
                // Totales
                document.getElementById('ver_subtotal').textContent = `$${parseFloat(data.subtotal || 0).toFixed(2)}`;
                document.getElementById('ver_descuento').textContent = `$${parseFloat(data.descuento || 0).toFixed(2)}`;
                document.getElementById('ver_impuesto').textContent = `$${parseFloat(data.impuesto || 0).toFixed(2)}`;
                document.getElementById('ver_total').textContent = `$${parseFloat(data.total || 0).toFixed(2)}`;
                
                // Detalles de productos
                const tbody = document.getElementById('detalles-compra');
                tbody.innerHTML = '';
                
                if (data.detalles && data.detalles.length > 0) {
                    data.detalles.forEach((detalle, index) => {
                        const row = tbody.insertRow();
                        row.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${detalle.producto_nombre || detalle.nombre || '—'}</td>
                            <td>${detalle.marca_modelo || detalle.marca || '—'}</td>
                            <td class="text-center">${detalle.cantidad || 0}</td>
                            <td class="text-end">$${parseFloat(detalle.precio_unitario || 0).toFixed(2)}</td>
                            <td class="text-end">$${parseFloat(detalle.descuento || 0).toFixed(2)}</td>
                            <td class="text-end">$${parseFloat(detalle.subtotal || 0).toFixed(2)}</td>
                        `;
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No hay productos en esta compra</td></tr>';
                }
                
                // Mostrar modal con limpieza previa
                window.limpiarBackdrops();
                const modalElement = document.getElementById('modalVerCompra');
                currentModal = new bootstrap.Modal(modalElement);
                currentModal.show();
                */
            })
            .catch(error => {
                console.error('Error:', error);
                modalBody.innerHTML = originalContent;
                alert(`Error al cargar los detalles: ${error.message}\n\nVerifica que la ruta /compras/${id}/detalles exista en tu backend`);
            });
    });
});