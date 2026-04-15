// public/js/compras/compras-estado.js

function initCambiarEstado() {
    document.querySelectorAll('.cambiar-estado').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const estado = this.dataset.estado;
            
            if (confirm('¿Confirmar recepción de la compra? Se actualizará el inventario automáticamente.')) {
                // Usar FormData con el token incluido
                const formData = new FormData();
                formData.append('estado', estado);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                
                fetch(`/compras/${id}/estado`, {
                    method: 'POST',
                    body: formData  // No headers Content-Type, el navegador lo pone automáticamente
                })
                .then(response => {
                    if (response.status === 419) {
                        alert('Sesión expirada. Recargando página...');
                        window.location.reload();
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.success) {
                        window.location.reload();
                    } else if (data && !data.success) {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cambiar estado');
                });
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initCambiarEstado();
});