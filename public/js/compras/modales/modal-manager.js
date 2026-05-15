/**
 * Modal Manager - Lógica mejorada: ocultar anterior, no superponer
 */

class ModalManager {
    constructor() {
        this.modalStack = [];
        this.currentModalId = null;
    }

    openModal(modalId, modalHTML, onClose = null) {

        // Si hay modal anterior, OCULTARLO COMPLETAMENTE
        if (this.currentModalId) {
            const anterior = document.getElementById(this.currentModalId);
            if (anterior) {
                anterior.style.display = 'none'; // ✅ OCULTAR, no oscurecer
                this.modalStack.push(this.currentModalId);
            }
        }

        // Crear modal nuevo
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        const modalDiv = document.getElementById(modalId);

        if (!modalDiv) {
            console.error('❌ Modal no encontrado:', modalId);
            return;
        }

        const bsModal = new bootstrap.Modal(modalDiv);
        bsModal.show();

        this.currentModalId = modalId;

        // Listener para cerrar
        modalDiv.addEventListener('hidden.bs.modal', () => {
            console.log('🔴 Cerrando:', modalId);
            modalDiv.remove();

            // Si hay anterior, mostrarlo
            if (this.modalStack.length > 0) {
                const anteriorId = this.modalStack.pop();
                const anteriorDiv = document.getElementById(anteriorId);

                if (anteriorDiv) {
                    anteriorDiv.style.display = ''; // ✅ MOSTRAR anterior
                    setTimeout(() => {
                        const bsAnterior = new bootstrap.Modal(anteriorDiv);
                        bsAnterior.show();
                        this.currentModalId = anteriorId;
                        console.log('✅ Mostrado:', anteriorId);
                    }, 200);
                }
            } else {
                // No hay más modales, limpiar
                this.currentModalId = null;
                document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
            }

            if (onClose) onClose();
        }, { once: true });
    }

    closeCurrentModal() {
        if (this.currentModalId) {
            const modal = document.getElementById(this.currentModalId);
            if (modal) {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) bsModal.hide();
            }
        }
    }

    hasBackModal() {
        return this.modalStack.length > 0;
    }
}

window.modalManager = new ModalManager();