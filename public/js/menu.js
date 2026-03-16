document.addEventListener("DOMContentLoaded", () => {
    const menuContainer = document.getElementById("menu");

    fetch("/menu.json")
        .then((response) => response.json())
        .then((data) => renderMenu(data, menuContainer))
        .catch((error) => console.error("Error cargando el menú:", error));
});

function renderMenu(items, container) {
    items.forEach((item) => {
        const li = document.createElement("li");

        const link = document.createElement("a");
        link.textContent = item.name;
        link.href = item.url || "#";
        li.appendChild(link);

        // Si tiene hijos → submenú
        if (item.children && item.children.length > 0) {
            li.classList.add("has-children");

            const subUl = document.createElement("ul");
            subUl.classList.add("submenu");

            item.children.forEach((child) => {
                const subLi = document.createElement("li");
                const subLink = document.createElement("a");

                subLink.textContent = child.name;
                subLink.href = child.url;

                subLi.appendChild(subLink);
                subUl.appendChild(subLi);
            });

            li.appendChild(subUl);

            // Evento para abrir/cerrar submenú
            link.addEventListener("click", (e) => {
                e.preventDefault();
                subUl.classList.toggle("show-submenu");
                li.classList.toggle("open");
            });
        }

        container.appendChild(li);
    });
}
