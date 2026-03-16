fetch('/api/sesiones-con-bloques')
    .then(res => {
        if (!res.ok) throw new Error('Error al cargar las sesiones');
        return res.json();
    })
    .then(data => {
        const contenedor = document.getElementById("sesiones");
        contenedor.innerHTML = ''; 

        if (data.length === 0) {
            contenedor.innerHTML = '<p style="text-align: center; color: #666;">No hay sesiones con bloques.</p>';
            return;
        }

        data.forEach(sesion => {
            const card = document.createElement("div");
            card.className = "sesion-card";

            const titulo = document.createElement("h2");
            titulo.className = "sesion-titulo";
            titulo.textContent = sesion.nombre;
            card.appendChild(titulo);

            if (sesion.fecha) {
                const fecha = document.createElement("p");
                fecha.className = "sesion-fecha";
                fecha.textContent = "📅 " + sesion.fecha;
                card.appendChild(fecha);
            }

            const subtitulo = document.createElement("h3");
            subtitulo.className = "bloques-subtitulo";
            subtitulo.textContent = "Bloques asignados:";
            card.appendChild(subtitulo);

            if (sesion.bloques && sesion.bloques.length > 0) {
                sesion.bloques.forEach(bloque => {
                    const bloqueDiv = document.createElement("div");
                    bloqueDiv.className = "bloque-item";

                    const infoDiv = document.createElement("div");
                    infoDiv.className = "bloque-info";

                    const nombre = document.createElement("div");
                    nombre.className = "bloque-nombre";
                    nombre.textContent = bloque.nombre;
                    infoDiv.appendChild(nombre);

                    const detalles = document.createElement("div");
                    detalles.className = "bloque-detalles";
                    detalles.innerHTML = `
                        <span>🔄 Repeticiones: ${bloque.pivot.repeticiones}</span>
                        <span>📍 Orden: ${bloque.pivot.orden}</span>
                        <span>📊 Tipo: ${bloque.tipo || 'No especificado'}</span>
                    `;
                    infoDiv.appendChild(detalles);

                    bloqueDiv.appendChild(infoDiv);

                    // Botón eliminar bloque individual
                    const btnEliminarBloque = document.createElement("button");
                    btnEliminarBloque.className = "btn-eliminar-bloque";
                    btnEliminarBloque.textContent = "Eliminar";
                    btnEliminarBloque.onclick = (e) => {
                        e.stopPropagation();
                        eliminarBloqueIndividual(bloque.pivot.id, bloqueDiv);
                    };

                    bloqueDiv.appendChild(btnEliminarBloque);
                    card.appendChild(bloqueDiv);
                });
            } else {
                const sinBloques = document.createElement("p");
                sinBloques.className = "sin-bloques";
                sinBloques.textContent = "Esta sesión no tiene bloques asignados.";
                card.appendChild(sinBloques);
            }

            // Botón eliminar TODA la sesión (solo relaciones)
            const btnEliminarSesion = document.createElement("button");
            btnEliminarSesion.className = "btn-eliminar-sesion";
            btnEliminarSesion.textContent = "Eliminar todos los bloques de esta sesión";

            btnEliminarSesion.addEventListener("click", () => {
                eliminarSesionCompleta(sesion.bloques, card);
            });

            card.appendChild(btnEliminarSesion);
            contenedor.appendChild(card);
        });
    })
    .catch(err => {
        console.error("Error:", err);
        document.getElementById("sesiones").innerHTML = 
            '<p class="error">Error al cargar las sesiones. Por favor, intenta de nuevo.</p>';
    });

// Eliminar un bloque individual
function eliminarBloqueIndividual(id, elemento) {
    if (!confirm("¿Eliminar este bloque de la sesión?")) return;

    fetch(`/sesion-bloque/${id}`, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Accept": "application/json"
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Error al eliminar');
        return res.json();
    })
    .then(() => {
        elemento.remove(); // Eliminar el elemento del DOM
        alert("Bloque eliminado correctamente");
    })
    .catch(err => {
        console.error("Error eliminando bloque:", err);
        alert("Error al eliminar el bloque");
    });
}

// Eliminar TODOS los bloques de una sesión
function eliminarSesionCompleta(bloques, card) {
    if (!confirm("¿Seguro que quieres eliminar TODOS los bloques de esta sesión?")) return;

    if (!bloques || bloques.length === 0) {
        alert("Esta sesión no tiene bloques para eliminar");
        return;
    }

    const peticiones = bloques.map(bloque =>
        fetch(`/sesion-bloque/${bloque.pivot.id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json"
            }
        })
    );

    Promise.all(peticiones)
        .then(() => {
            alert("Todos los bloques eliminados correctamente");
            card.remove(); // Eliminar toda la tarjeta
        })
        .catch(err => {
            console.error("Error eliminando sesión:", err);
            alert("Error al eliminar algunos bloques");
        });
}