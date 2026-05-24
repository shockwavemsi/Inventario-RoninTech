const navToggle = document.querySelector("[data-nav-toggle]");
const nav = document.querySelector("[data-nav]");
const header = document.querySelector("[data-header]");

navToggle?.addEventListener("click", () => {
    const isOpen = nav.classList.toggle("is-open");
    navToggle.setAttribute("aria-expanded", String(isOpen));
});

nav?.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
        nav.classList.remove("is-open");
        navToggle?.setAttribute("aria-expanded", "false");
    });
});

window.addEventListener("scroll", () => {
    header?.classList.toggle("is-scrolled", window.scrollY > 10);
});
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('demoForm');
    const statusDiv = document.getElementById('formStatus');
    const scriptURL = 'https://script.google.com/macros/s/AKfycbyclPcIIEa8Y6LjcnWQt59kRSjfgo21o30tBcpnKYt9aKgpySARqCSvfBy-h3fNUZTVxg/exec'; // <-- AQUÍ TU URL

    form.addEventListener('submit', (e) => {
        e.preventDefault(); // Evita que la página se recargue

        // Mostrar mensaje de "Enviando..."
        statusDiv.innerHTML = '<p style="color: blue;">Enviando solicitud, por favor espera...</p>';

        const formData = new FormData(form);
        const dataToSend = {};
        formData.forEach((value, key) => { dataToSend[key] = value; });

        fetch(scriptURL, {
            method: 'POST',
            mode: 'no-cors', // Ayuda a evitar problemas de CORS
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams(dataToSend)
        })
        .then(() => {
            // Con 'no-cors', la respuesta no es accesible. Asumimos éxito.
            statusDiv.innerHTML = '<p style="color: green;">✅ ¡Solicitud enviada con éxito! Redirigiendo...</p>';
            // Limpiar el formulario
            form.reset();
            // Esperar 2 segundos y redirigir a la misma página, sección #demo
            setTimeout(() => {
                window.location.href = window.location.pathname + '#demo';
            }, 2000);
        })
        .catch((error) => {
            console.error('Error:', error);
            statusDiv.innerHTML = '<p style="color: red;">❌ Hubo un error al enviar la solicitud. Inténtalo de nuevo.</p>';
        });
    });
});