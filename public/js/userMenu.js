document.addEventListener('DOMContentLoaded', function() {
    cargarMenuUsuario();
});

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
                }
            });
        })
        .catch(error => {
            console.error('Error al cargar el menú de usuario:', error);
        });
}