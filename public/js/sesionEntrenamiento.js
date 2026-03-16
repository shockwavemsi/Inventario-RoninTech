let offset = 0;
        const limit = 10;
        let cargando = false;
        let fin = false;

        function cargarSesiones() {
            if (cargando || fin) return;
            cargando = true;

            fetch(`/api/sesiones?offset=${offset}&limit=${limit}`)
                .then(res => res.json())
                .then(data => {

                    if (data.length === 0) {
                        fin = true;
                        return;
                    }

                    const contenedor = document.getElementById('sesiones');

                    data.forEach(sesion => {
                        const card = document.createElement('div');
                        card.classList.add('sesion-card');

                        const titulo = document.createElement('h2');
                        titulo.textContent = sesion.nombre;
                        card.appendChild(titulo);

                        const fecha = document.createElement('p');
                        fecha.textContent = "Fecha: " + sesion.fecha;
                        card.appendChild(fecha);

                        const descripcion = document.createElement('p');
                        descripcion.textContent = "Descripción: " + sesion.descripcion;
                        card.appendChild(descripcion);

                        const completada = document.createElement('p');
                        completada.textContent = "Completada: " + (sesion.completada ? "Sí" : "No");
                        card.appendChild(completada);

                        // Botón eliminar
                        const btnEliminar = document.createElement("button");
                        btnEliminar.textContent = "Eliminar";
                        btnEliminar.addEventListener("click", () => eliminarSesion(sesion.id, card));
                        card.appendChild(btnEliminar);

                        contenedor.appendChild(card);
                    });

                    offset += limit;
                    cargando = false;
                })
                .catch(err => console.error(err));
        }

        // Scroll infinito
        window.addEventListener('scroll', () => {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 50) {
                cargarSesiones();
            }
        });

        // Cargar primeras sesiones
        cargarSesiones();

        function eliminarSesion(id, card) {
            if (!confirm("¿Seguro que quieres eliminar esta sesión?")) return;

            fetch(`/sesion/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json"
                }
            })
                .then(res => res.json())
                .then(() => card.remove())
                .catch(err => console.error(err));
        }