function initEliminarCompra() {
    document.querySelectorAll('.eliminar-compra').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            // if (!confirm('¿Eliminar esta compra?')) return;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const fila = btn.closest('tr');

            fetch(`/compras/${id}/eliminar`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                console.log('Status:', res.status);
                return res.json().catch(() => ({}));
            })
            .then(data => {
                console.log('Data:', data);
                if (fila) fila.remove();
                const restantes = document.querySelectorAll('#tabla-compras tr').length;
                const contador = document.getElementById('contador');
                if (contador) {
                    contador.innerHTML = `<i class="bi bi-info-circle"></i> <strong>Mostrando ${restantes} compras</strong>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('danger', 'Error: ' + error.message);
            });
        });
    });
}

document.addEventListener('DOMContentLoaded', initEliminarCompra);