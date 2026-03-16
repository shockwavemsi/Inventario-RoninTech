function toggleDetalle(id) {
    const detalle = document.getElementById("detalle-" + id);
    const icon = document.getElementById("icon-" + id);

    if (detalle.classList.contains("visible")) {
        detalle.classList.remove("visible");
        icon.textContent = "▼";
    } else {
        detalle.classList.add("visible");
        icon.textContent = "▲";

        if (
            detalle.querySelector(".loading") ||
            detalle.children.length === 1
        ) {
            cargarDetalle(id, detalle);
        }
    }
}

function cargarDetalle(id, contenedor) {
    fetch(`/resultado/${id}`)
        .then((response) => {
            if (!response.ok) {
                throw new Error("Error al cargar detalles");
            }
            return response.json();
        })
        .then((data) => {
            // Limpiar contenedor
            while (contenedor.firstChild) {
                contenedor.removeChild(contenedor.firstChild);
            }

            // Crear grid de detalles
            const grid = document.createElement("div");
            grid.className = "detalle-grid";

            grid.appendChild(
                crearDetalleItem("Pulso medio", data.pulso_medio, "ppm"),
            );
            grid.appendChild(
                crearDetalleItem("Pulso máximo", data.pulso_max, "ppm"),
            );
            grid.appendChild(
                crearDetalleItem("Potencia media", data.potencia_media, "W"),
            );
            grid.appendChild(
                crearDetalleItem(
                    "Potencia normalizada",
                    data.potencia_normalizada,
                    "W",
                ),
            );
            grid.appendChild(
                crearDetalleItem(
                    "Velocidad media",
                    data.velocidad_media,
                    "km/h",
                ),
            );
            grid.appendChild(
                crearDetalleItem("TSS", data.puntos_estres_tss, ""),
            );
            grid.appendChild(
                crearDetalleItem("IF", data.factor_intensidad_if, ""),
            );
            grid.appendChild(
                crearDetalleItem("Ascenso", data.ascenso_metros, "m"),
            );

            contenedor.appendChild(grid);

            // Recorrido si existe
            if (data.recorrido) {
                const recorridoItem = document.createElement("div");
                recorridoItem.className = "detalle-item";
                recorridoItem.style.gridColumn = "span 2";

                const label = document.createElement("div");
                label.className = "detalle-label";
                label.textContent = "Recorrido";

                const value = document.createElement("div");
                value.className = "detalle-value";
                value.textContent = data.recorrido;

                recorridoItem.appendChild(label);
                recorridoItem.appendChild(value);
                contenedor.appendChild(recorridoItem);
            }

            // Comentario si existe
            if (data.comentario) {
                const comentarioDiv = document.createElement("div");
                comentarioDiv.className = "comentario";

                const strong = document.createElement("strong");
                strong.textContent = "📝 Comentario: ";

                const texto = document.createTextNode(data.comentario);

                comentarioDiv.appendChild(strong);
                comentarioDiv.appendChild(texto);
                contenedor.appendChild(comentarioDiv);
            }
        })
        .catch((error) => {
            console.error("Error:", error);

            while (contenedor.firstChild) {
                contenedor.removeChild(contenedor.firstChild);
            }

            const errorDiv = document.createElement("div");
            errorDiv.className = "error-carga";
            errorDiv.textContent = "Error al cargar los detalles";
            contenedor.appendChild(errorDiv);
        });
}

function crearDetalleItem(label, valor, unidad) {
    const item = document.createElement("div");
    item.className = "detalle-item";

    const labelDiv = document.createElement("div");
    labelDiv.className = "detalle-label";
    labelDiv.textContent = label;

    const valueDiv = document.createElement("div");
    valueDiv.className = "detalle-value";

    const valorTexto = valor ?? "-";
    valueDiv.textContent = valorTexto;

    if (unidad && valorTexto !== "-") {
        const unidadSpan = document.createElement("span");
        unidadSpan.className = "unidad";
        unidadSpan.textContent = unidad;
        valueDiv.appendChild(unidadSpan);
    }

    item.appendChild(labelDiv);
    item.appendChild(valueDiv);

    return item;
}
