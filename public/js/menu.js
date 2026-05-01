document.addEventListener('DOMContentLoaded', function() {
    cargarMenu();
    inicializarHamburguesa();
});

// ==================== MENÚ DINÁMICO ====================
function cargarMenu() {
    fetch('/menu.json')
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

                        // Cerrar sidebar al click en submenú (móvil)
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

                    // Cerrar sidebar al click en enlace (móvil)
                    enlace.addEventListener('click', function() {
                        cerrarMenuMobil();
                    });
                }
            });
        })
        .catch(error => {
            console.error('Error al cargar el menú:', error);
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
        sidebarOverlay?.classList.toggle('open');
    });

    // Click en overlay: cerrar menú
    sidebarOverlay?.addEventListener('click', function() {
        cerrarMenuMobil();
    });
}

// Función para cerrar el menú en móvil
function cerrarMenuMobil() {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    if (window.innerWidth <= 1919) {
        sidebar?.classList.remove('open');
        menuToggle?.classList.remove('open');
        sidebarOverlay?.classList.remove('open');
    }
}

// Cerrar menú al cambiar tamaño de ventana
window.addEventListener('resize', function() {
    if (window.innerWidth >= 1920) {
        cerrarMenuMobil();
    }
});
