// public/js/modal-fix.js
let currentModal = null;

window.limpiarBackdrops = function() {
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(backdrop => backdrop.remove());
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
};

document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('modalVerCompra');
    if (modalElement) {
        modalElement.addEventListener('hidden.bs.modal', function() {
            window.limpiarBackdrops();
            if (currentModal) {
                currentModal.dispose();
                currentModal = null;
            }
        });
    }
});