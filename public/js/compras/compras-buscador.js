// Inicializar buscador
function initBuscador() {
    const buscador = document.getElementById('buscador');
    if (!buscador) return;
    
    const filas = document.querySelectorAll('#tabla-compras tr');
    const total = filas.length;
    const contador = document.getElementById('contador');
    
    buscador.addEventListener('keyup', function() {
        const filtro = this.value.toLowerCase();
        let visibles = 0;
        
        filas.forEach(fila => {
            const codigo = fila.querySelector('.codigo')?.textContent.toLowerCase() || '';
            const proveedor = fila.querySelector('.proveedor')?.textContent.toLowerCase() || '';
            
            if (codigo.includes(filtro) || proveedor.includes(filtro)) {
                fila.style.display = '';
                visibles++;
            } else {
                fila.style.display = 'none';
            }
        });
        
        if (contador) {
            contador.innerHTML = `<strong>Mostrando ${visibles} de ${total} compras</strong>`;
        }
    });
}