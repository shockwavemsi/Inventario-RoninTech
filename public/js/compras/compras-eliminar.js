// public/js/compras/compras-eliminar.js

function eliminarCompra(btnElement, id) {
    if (!confirm('¿Seguro que deseas eliminar esta compra?')) {
        return;
    }
    
    const fila = btnElement.closest('tr');
    btnElement.disabled = true;
    
    fetch(`/compras/${id}/eliminar`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (fila) fila.remove();
            const restantes = document.querySelectorAll('#tabla-compras tr').length;
            const contador = document.getElementById('contador');
            if (contador) {
                contador.innerHTML = `<strong>Mostrando ${restantes} compras</strong>`;
            }
            if (typeof mostrarAlerta === 'function') {
                mostrarAlerta('success', data.message);
            }
        } else {
            if (typeof mostrarAlerta === 'function') {
                mostrarAlerta('danger', data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof mostrarAlerta === 'function') {
            mostrarAlerta('danger', 'Error de conexión');
        }
    });
}