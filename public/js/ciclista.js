fetch('/api/ciclista')
        .then(response => response.json())
        .then(data => {

            const contenedor = document.getElementById('datos-ciclista');
            const c = data.ciclista;

            const nombre = document.createElement('p');
            nombre.textContent = "Nombre: " + c.nombre + " " + c.apellidos;
            contenedor.appendChild(nombre);

            const email = document.createElement('p');
            email.textContent = "Email: " + c.email;
            contenedor.appendChild(email);

            const fechaNac = document.createElement('p');
            fechaNac.textContent = "Fecha nacimiento: " + c.fecha_nacimiento;
            contenedor.appendChild(fechaNac);

            const pesoBase = document.createElement('p');
            pesoBase.textContent = "Peso base: " + c.peso_base + " kg";
            contenedor.appendChild(pesoBase);

            const alturaBase = document.createElement('p');
            alturaBase.textContent = "Altura base: " + c.altura_base + " cm";
            contenedor.appendChild(alturaBase);

            const tbody = document.getElementById('tabla-historico');

            data.historico.forEach(h => {

                const fila = document.createElement('tr');

                function crearCelda(valor) {
                    const td = document.createElement('td');
                    td.textContent = valor;
                    return td;
                }

                fila.appendChild(crearCelda(h.fecha));
                fila.appendChild(crearCelda(h.peso));
                fila.appendChild(crearCelda(h.ftp));
                fila.appendChild(crearCelda(h.pulso_max));
                fila.appendChild(crearCelda(h.pulso_reposo));
                fila.appendChild(crearCelda(h.potencia_max));
                fila.appendChild(crearCelda(h.grasa_corporal));
                fila.appendChild(crearCelda(h.vo2max));
                fila.appendChild(crearCelda(h.comentario));

                tbody.appendChild(fila);
            });

        })
        .catch(error => console.error('Error:', error));