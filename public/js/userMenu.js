document.addEventListener('DOMContentLoaded', function() {
    cargarMenuUsuario();
    inicializarHamburguesa();
});

// ==================== MENÚ DINÁMICO (usuario) ====================
function cargarMenuUsuario() {
    fetch('/userMenu.json')
        .then(response => response.json())
        .then(data => {
            const contenedor = document.getElementById('menu-contenedor');
            if (!contenedor) return;

            contenedor.innerHTML = '';

            data.menu.forEach(item => {
                if (item.submenu && item.submenu.length > 0) {
                    // Elemento con submenú
                    const enlace = document.createElement('a');
                    enlace.href = item.url;
                    enlace.textContent = item.nombre;
                    enlace.classList.add('has-submenu');

                    const submenuDiv = document.createElement('div');
                    submenuDiv.classList.add('submenu');

                    item.submenu.forEach(sub => {
                        const subEnlace = document.createElement('a');
                        subEnlace.href = sub.url;
                        subEnlace.textContent = sub.nombre;
                        submenuDiv.appendChild(subEnlace);

                        // Cerrar sidebar al hacer clic en submenú (móvil)
                        subEnlace.addEventListener('click', function() {
                            cerrarMenuMobil();
                        });
                    });

                    contenedor.appendChild(enlace);
                    contenedor.appendChild(submenuDiv);

                    enlace.addEventListener('click', function(e) {
                        e.preventDefault();
                        enlace.classList.toggle('open');
                        submenuDiv.classList.toggle('show');
                    });

                } else {
                    // Elemento sin submenú
                    const enlace = document.createElement('a');
                    enlace.href = item.url;
                    enlace.textContent = item.nombre;
                    contenedor.appendChild(enlace);

                    // Cerrar sidebar al hacer clic en enlace (móvil)
                    enlace.addEventListener('click', function() {
                        cerrarMenuMobil();
                    });
                }
            });
        })
        .catch(error => {
            console.error('Error al cargar el menú de usuario:', error);
        });
}

// ==================== HAMBURGUESA Y OVERLAY ====================
function inicializarHamburguesa() {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    if (!menuToggle || !sidebar) return;

    // Click en hamburguesa: abrir/cerrar menú
    menuToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        sidebar.classList.toggle('open');
        menuToggle.classList.toggle('open');
        if (sidebarOverlay) sidebarOverlay.classList.toggle('open');
    });

    // Click en overlay: cerrar menú
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            cerrarMenuMobil();
        });
    }
}

// Función para cerrar el menú en móvil
function cerrarMenuMobil() {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    if (window.innerWidth <= 1919) {
        if (sidebar) sidebar.classList.remove('open');
        if (menuToggle) menuToggle.classList.remove('open');
        if (sidebarOverlay) sidebarOverlay.classList.remove('open');
    }
}

// Cerrar menú al cambiar tamaño de ventana (si pasa a escritorio)
window.addEventListener('resize', function() {
    if (window.innerWidth >= 1920) {
        cerrarMenuMobil();
    }
});