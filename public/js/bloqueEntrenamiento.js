// CAMBIO IMPORTANTE: ahora usa /api/bloques en lugar de /api/bloques-del-ciclista
        fetch('/api/bloques')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                const contenedor = document.getElementById('bloques');
                
                // Limpiar contenedor por si acaso
                contenedor.innerHTML = '';

                data.forEach(bloque => {
                    const card = document.createElement('div');
                    card.classList.add('bloque-card');
                    card.setAttribute('data-id', bloque.id);  // Bueno para identificar

                    // Título
                    const titulo = document.createElement('h2');
                    titulo.textContent = bloque.nombre || 'Sin nombre';
                    card.appendChild(titulo);

                    // Descripción
                    const descripcion = document.createElement('p');
                    descripcion.textContent = "Descripción: " + (bloque.descripcion || 'Sin descripción');
                    card.appendChild(descripcion);

                    // Tipo
                    const tipo = document.createElement('p');
                    tipo.textContent = "Tipo: " + (bloque.tipo || 'No especificado');
                    card.appendChild(tipo);

                    // Duración
                    const duracion = document.createElement('p');
                    duracion.textContent = "Duración estimada: " + (bloque.duracion_estimada || 'No especificada');
                    card.appendChild(duracion);

                    // Potencia
                    const potencia = document.createElement('p');
                    potencia.textContent = "Potencia: " + 
                        (bloque.potencia_pct_min || '?') + "% - " + 
                        (bloque.potencia_pct_max || '?') + "%";
                    card.appendChild(potencia);

                    // Pulso
                    const pulso = document.createElement('p');
                    pulso.textContent = "Pulso reserva: " + (bloque.pulso_reserva_pct || '?') + "%";
                    card.appendChild(pulso);

                    // Comentario
                    const comentario = document.createElement('p');
                    comentario.textContent = "Comentario: " + (bloque.comentario || 'Sin comentario');
                    card.appendChild(comentario);

                    // Botón eliminar
                    const btnEliminar = document.createElement("button");
                    btnEliminar.textContent = "Eliminar";
                    btnEliminar.style.marginTop = "10px";

                    btnEliminar.addEventListener("click", () => {
                        eliminarBloque(bloque.id, card);
                    });

                    card.appendChild(btnEliminar);

                    // Separador
                    const hr = document.createElement('hr');
                    card.appendChild(hr);

                    contenedor.appendChild(card);
                });
            })
            .catch(error => {
                console.error("Error:", error);
                document.getElementById('bloques').innerHTML = 
                    '<p style="color: red;">Error al cargar los bloques. Por favor, intenta de nuevo.</p>';
            });

        function eliminarBloque(id, card) {
            if (!confirm("¿Seguro que quieres eliminar este bloque?")) return;

            fetch(`/bloque/${id}/eliminar`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }
            })
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Error en la eliminación');
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    card.remove();
                    alert("Bloque eliminado correctamente");
                })
                .catch(err => {
                    console.error("Error eliminando bloque:", err);
                    alert("Error al eliminar el bloque");
                });
        }