// public/js/compras/compras-actualizar.js
// ✅ NUEVO ARCHIVO: Actualiza la página después de crear una compra

document.addEventListener('DOMContentLoaded', function() {
    console.log('⚡ compras-actualizar.js cargado');

    const form = document.getElementById('formCrearCompra');

    if (form) {
        console.log('📝 Formulario de crear compra encontrado');

        form.addEventListener('submit', function(e) {
            // Permitir el submit (ir al servidor)
            // Mostrar mensaje de carga
            console.log('📤 Formulario enviado al servidor...');

            // Opcional: Mostrar loading
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-hourglass"></i> Guardando...';
            }

            // Después de 2 segundos (esperar respuesta del servidor), recargar página
            // Esto traerá los datos actualizados del servidor
            setTimeout(() => {
                console.log('🔄 Recargando página...');
                location.reload();
            }, 2000);
        });
    } else {
        console.log('⚠️ Formulario formCrearCompra no encontrado en esta página');
    }
});