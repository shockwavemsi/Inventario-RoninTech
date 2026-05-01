// public/js/compras/compras.js

// Variables globales
let productosTemp = [];
let productoIndex = 0;

// Función auxiliar para mostrar alertas
function mostrarAlerta(tipo, mensaje) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
    alertDiv.innerHTML = `${mensaje}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
    const content = document.querySelector('.content');
    const titulo = content.querySelector('h1');
    content.insertBefore(alertDiv, titulo.nextSibling);
    setTimeout(() => {
        if (alertDiv) alertDiv.remove();
    }, 3000);
}

// Función para cargar el siguiente número de factura
function cargarSiguienteNumeroFactura() {
    fetch('/compras/ultimo-numero')
        .then(res => res.json())
        .then(data => {
            const preview = document.getElementById('preview_numero_factura');
            const hidden = document.getElementById('numero_factura_hidden');
            if (preview) preview.value = data.numero_factura;
            if (hidden) hidden.value = data.numero_factura;
        })
        .catch(error => console.error('Error:', error));
}

// ✅ Inicializar cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('⚡ compras.js inicializado');

    // Inicializar módulos en orden correcto
    if (typeof cargarSiguienteNumeroFactura === 'function') {
        cargarSiguienteNumeroFactura();
    }

    if (typeof initBuscador === 'function') {
        console.log('✅ Inicializando buscador...');
        initBuscador();
    }

    if (typeof initAccionesCompra === 'function') {
        console.log('✅ Inicializando acciones compra...');
        initAccionesCompra();
    }

    if (typeof initEliminarCompra === 'function') {
        console.log('✅ Inicializando eliminar compra...');
        initEliminarCompra();
    }
});