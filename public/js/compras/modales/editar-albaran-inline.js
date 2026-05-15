/**
 * Editar Albarán Inline - Doble clic para editar campo
 * Módulo ES6 para edición rápida sin modales
 */

class EditarAlbaranInline {

    static init() {
        console.log('📝 Módulo de edición inline cargado');
        this.activarEditables();
    }

    static activarEditables() {
        document.addEventListener('dblclick', (e) => {
            const fila = e.target.closest('tr[data-albaran-id]');
            if (!fila) return;

            const albaranId = fila.dataset.albaranId;
            const campo = e.target.dataset.editable;

            console.log('🔍 Doble clic detectado:', { albaranId, campo });

            if (campo) {
                this.activarEdicion(e.target, albaranId, campo);
            }
        });
    }

    static activarEdicion(elemento, albaranId, campo) {
        const valorActual = elemento.textContent.trim();

        console.log('✏️ Activando edición:', { campo, valorActual });

        if (campo === 'fecha_recepcion') {
            const input = document.createElement('input');
            input.type = 'date';
            input.value = valorActual.replace(/\//g, '-').split('-').reverse().join('-'); // Convertir d/m/Y a Y-m-d
            input.className = 'form-control form-control-sm';
            input.style.width = '100%';

            elemento.replaceWith(input);
            input.focus();

            input.addEventListener('blur', () => this.guardarCambio(input, albaranId, campo, elemento));
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') this.guardarCambio(input, albaranId, campo, elemento);
            });

        } else if (campo === 'observaciones') {
            const textarea = document.createElement('textarea');
            textarea.value = valorActual;
            textarea.className = 'form-control form-control-sm';
            textarea.style.width = '100%';
            textarea.rows = 3;

            elemento.replaceWith(textarea);
            textarea.focus();

            textarea.addEventListener('blur', () => this.guardarCambio(textarea, albaranId, campo, elemento));
            textarea.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && e.ctrlKey) {
                    this.guardarCambio(textarea, albaranId, campo, elemento);
                }
            });
        }
    }

    static guardarCambio(input, albaranId, campo, elementoOriginal) {
        let nuevoValor = input.value;

        // Convertir Y-m-d a d/m/Y para mostrar
        if (campo === 'fecha_recepcion' && nuevoValor) {
            const [año, mes, día] = nuevoValor.split('-');
            const displayValue = `${día}/${mes}/${año}`;
        }

        console.log('💾 Guardando cambio:', { albaranId, campo, nuevoValor });

        // AJAX para actualizar
        fetch(`/albaranes/${albaranId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                [campo]: nuevoValor
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                console.log('✅ Cambio guardado correctamente');
                
                const span = document.createElement('span');
                
                // Mostrar valor formateado
                if (campo === 'fecha_recepcion' && nuevoValor) {
                    const [año, mes, día] = nuevoValor.split('-');
                    span.textContent = `${día}/${mes}/${año}`;
                } else {
                    span.textContent = nuevoValor || '—';
                }
                
                span.dataset.editable = campo;
                span.style.cursor = 'pointer';
                span.style.padding = '0.5rem';
                span.title = '🔄 Doble clic para editar';
                
                input.replaceWith(span);
            } else {
                alert('❌ Error: ' + data.message);
                input.replaceWith(elementoOriginal);
            }
        })
        .catch(err => {
            console.error('❌ Error en AJAX:', err);
            alert('Error al guardar');
            input.replaceWith(elementoOriginal);
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    EditarAlbaranInline.init();
});

export default EditarAlbaranInline;
