// js/crearSesionBloque.js

// ======== FUNCIÓN PARA CAMBIAR ENTRE SESIÓN EXISTENTE Y NUEVA ========
function toggleSesionForm() {
    // Obtener el radio seleccionado
    const radios = document.getElementsByName("tipo_sesion");
    let tipoSeleccionado = "existente";

    for (let i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            tipoSeleccionado = radios[i].value;
            break;
        }
    }

    console.log("Tipo seleccionado:", tipoSeleccionado);

    // Obtener los contenedores
    const existente = document.getElementById("sesion-existente");
    const nueva = document.getElementById("sesion-nueva");

    if (!existente || !nueva) {
        console.error("No se encontraron los contenedores");
        return;
    }

    if (tipoSeleccionado === "existente") {
        // Mostrar sesión existente, ocultar nueva
        existente.classList.remove("hidden");
        nueva.classList.add("hidden");

        // Actualizar required
        document.getElementById("id_sesion_entrenamiento").required = true;
        document.getElementById("nueva_sesion_nombre").required = false;
        document.getElementById("nueva_sesion_id_plan").required = false;

        console.log("Mostrando sesión existente");
    } else {
        // Mostrar nueva sesión, ocultar existente
        existente.classList.add("hidden");
        nueva.classList.remove("hidden");

        // Actualizar required
        document.getElementById("id_sesion_entrenamiento").required = false;
        document.getElementById("nueva_sesion_nombre").required = true;
        document.getElementById("nueva_sesion_id_plan").required = true;

        console.log("Mostrando nueva sesión");
    }
}

// ======== FUNCIÓN PARA CREAR BLOQUE VÍA AJAX ========
function crearBloque() {
    // Recoger datos del formulario
    const datos = {
        nombre: document.getElementById("nombre")?.value || "",
        descripcion: document.getElementById("descripcion")?.value || "",
        tipo: document.getElementById("tipo")?.value || "",
        duracion_estimada: document.getElementById("duracion")?.value || "",
        potencia_pct_min: document.getElementById("potencia_min")?.value || "",
        potencia_pct_max: document.getElementById("potencia_max")?.value || "",
        pulso_reserva_pct: document.getElementById("pulso")?.value || "",
        comentario: document.getElementById("comentario")?.value || "",
        _token:
            document.querySelector('meta[name="csrf-token"]')?.content || "",
    };

    // Validar campos obligatorios
    if (
        !datos.nombre ||
        !datos.tipo ||
        !datos.duracion_estimada ||
        !datos.potencia_pct_min ||
        !datos.potencia_pct_max ||
        !datos.pulso_reserva_pct
    ) {
        alert("⚠️ Por favor completa todos los campos obligatorios");
        return;
    }

    // Validar formato de duración (HH:MM:SS)
    const duracionRegex = /^([0-9]{2}):([0-5][0-9]):([0-5][0-9])$/;
    if (!duracionRegex.test(datos.duracion_estimada)) {
        alert("⚠️ La duración debe tener formato HH:MM:SS (ej: 00:08:00)");
        return;
    }

    // Enviar petición AJAX
    fetch("/api/bloques/crear-rapido", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                ?.content,
            Accept: "application/json",
        },
        body: JSON.stringify(datos),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Error en la respuesta del servidor");
            }
            return response.json();
        })
        .then((data) => {
            if (data.error) {
                alert("❌ Error: " + data.error);
            } else {
                // Mostrar mensaje de éxito
                const mensaje = document.getElementById("mensaje-exito");
                mensaje.textContent =
                    '✅ Bloque "' + data.nombre + '" creado correctamente';
                mensaje.classList.add("visible");

                // Limpiar formulario
                document.getElementById("nombre").value = "";
                document.getElementById("descripcion").value = "";
                document.getElementById("tipo").value = "";
                document.getElementById("duracion").value = "";
                document.getElementById("potencia_min").value = "";
                document.getElementById("potencia_max").value = "";
                document.getElementById("pulso").value = "";
                document.getElementById("comentario").value = "";

                // Añadir el nuevo bloque al select de la derecha
                const select = document.getElementById("bloques-disponibles");
                const option = document.createElement("option");
                option.value = data.id;
                option.textContent =
                    data.nombre + " (" + (data.tipo || "sin tipo") + ")";
                select.appendChild(option);

                // Seleccionar automáticamente el nuevo bloque
                option.selected = true;

                // Ocultar mensaje después de 3 segundos
                setTimeout(() => {
                    mensaje.classList.remove("visible");
                    setTimeout(() => {
                        mensaje.textContent = "";
                    }, 300);
                }, 3000);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("❌ Error al crear el bloque. Por favor, intenta de nuevo.");
        });
}

// ======== VALIDACIÓN DEL FORMULARIO DE ASIGNACIÓN ========
function validarFormularioAsignar(event) {
    const tipo = document.querySelector(
        'input[name="tipo_sesion"]:checked',
    )?.value;

    if (!tipo) {
        event.preventDefault();
        alert("⚠️ Debes seleccionar un tipo de sesión");
        return false;
    }

    const bloquesSelect = document.getElementById("bloques-disponibles");
    if (!bloquesSelect || bloquesSelect.selectedOptions.length === 0) {
        event.preventDefault();
        alert("⚠️ Debes seleccionar al menos un bloque para asignar");
        return false;
    }

    if (tipo === "existente") {
        const sesionExistente = document.getElementById(
            "id_sesion_entrenamiento",
        );
        if (!sesionExistente || !sesionExistente.value) {
            event.preventDefault();
            alert("⚠️ Debes seleccionar una sesión existente");
            return false;
        }
    } else {
        const nuevaSesion = document.getElementById("nueva_sesion_nombre");
        if (!nuevaSesion || !nuevaSesion.value.trim()) {
            event.preventDefault();
            alert("⚠️ Debes ingresar un nombre para la nueva sesión");
            return false;
        }
    }

    const repeticiones = document.getElementById("repeticiones");
    if (!repeticiones || repeticiones.value < 1) {
        event.preventDefault();
        alert("⚠️ Las repeticiones deben ser al menos 1");
        return false;
    }

    return true;
}

// ======== INICIALIZACIÓN ========
document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM cargado - Inicializando formulario");

    // Ejecutar toggle inicial para establecer el estado correcto
    toggleSesionForm();

    // Agregar validación al formulario de asignación
    const formAsignar = document.getElementById("form-asignar");
    if (formAsignar) {
        formAsignar.addEventListener("submit", validarFormularioAsignar);
    }

    console.log("Inicialización completa");
});
