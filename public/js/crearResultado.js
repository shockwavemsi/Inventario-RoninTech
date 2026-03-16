document
    .getElementById("resultadoForm")
    .addEventListener("submit", function (e) {
        e.preventDefault();

        // Resetear estilos de error
        document.querySelectorAll(".required").forEach((input) => {
            input.classList.remove("error-input");
        });
        document.querySelectorAll(".error-message").forEach((msg) => {
            msg.classList.remove("visible");
        });

        // Validar campos obligatorios
        let isValid = true;
        const camposObligatorios = [
            { id: "id_sesion", mensaje: "Debes seleccionar una sesión" },
            { id: "id_bicicleta", mensaje: "Debes seleccionar una bicicleta" },
            { id: "fecha", mensaje: "Debes indicar la fecha" },
            { id: "hora", mensaje: "Debes indicar la hora" },
            { id: "duracion", mensaje: "Debes indicar la duración" },
            { id: "kilometros", mensaje: "Debes indicar los kilómetros" },
            { id: "recorrido", mensaje: "Debes describir el recorrido" },
            {
                id: "potencia_normalizada",
                mensaje: "Debes indicar la potencia normalizada",
            },
            {
                id: "velocidad_media",
                mensaje: "Debes indicar la velocidad media",
            },
        ];

        camposObligatorios.forEach((campo) => {
            const elemento = document.getElementById(campo.id);
            const errorElement = document.getElementById(`error-${campo.id}`);

            if (!elemento.value || elemento.value === "") {
                isValid = false;
                elemento.classList.add("error-input");
                if (errorElement) {
                    errorElement.classList.add("visible");
                }
            }
        });

        if (isValid) {
            // Combinar fecha y hora antes de enviar
            const fecha = document.getElementById("fecha").value;
            const hora = document.getElementById("hora").value;
            const fechaHora = `${fecha}T${hora}`;

            // Crear input oculto con fecha completa
            const fechaCompleta = document.createElement("input");
            fechaCompleta.type = "hidden";
            fechaCompleta.name = "fecha_completa";
            fechaCompleta.value = fechaHora;
            this.appendChild(fechaCompleta);

            this.submit();
        } else {
            const notification = document.getElementById("notification");
            notification.style.display = "block";
            setTimeout(() => {
                notification.style.display = "none";
            }, 3000);
        }
    });

// Quitar error al interactuar
document.querySelectorAll(".required").forEach((input) => {
    input.addEventListener("input", function () {
        this.classList.remove("error-input");
        const errorId = `error-${this.id}`;
        const errorElement = document.getElementById(errorId);
        if (errorElement) {
            errorElement.classList.remove("visible");
        }
    });

    if (input.tagName === "SELECT") {
        input.addEventListener("change", function () {
            this.classList.remove("error-input");
            const errorId = `error-${this.id}`;
            const errorElement = document.getElementById(errorId);
            if (errorElement) {
                errorElement.classList.remove("visible");
            }
        });
    }
});
