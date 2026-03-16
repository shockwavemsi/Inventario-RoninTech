<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Bloques y Asignar a Sesión</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/formularios/crearSesionBloqueEntrenamientos.css') }}">
    <style>
      
    </style>
</head>
<body>
    <h1>Crear Bloques y Asignar a Sesión</h1>

    <!-- AVISO DE ÉXITO (desde sesión) -->
    @if(session('success'))
        <div class="aviso-exito">
            ✅ {{ session('success') }}
        </div>
    @endif

    <!-- AVISO DE ERRORES (desde validación) -->
    @if($errors->any())
        <div class="aviso-error">
            <strong>❌ Errores:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <!-- Mensaje de éxito para AJAX (inicialmente oculto) -->
    <div id="mensaje-exito" class="mensaje-exito"></div>
    
    <div class="row">
        <!-- COLUMNA IZQUIERDA: Crear Nuevo Bloque -->
        <div class="columna">
            <h2>➕ Crear Nuevo Bloque</h2>
            
            <form id="form-crear-bloque">
                @csrf
                
                <div class="form-group">
                    <label>Nombre:</label>
                    <input type="text" id="nombre" placeholder="Ej: Sweet Spot" required>
                </div>
                
                <div class="form-group">
                    <label>Descripción:</label>
                    <input type="text" id="descripcion" placeholder="Ej: Intervalos Sweet Spot">
                </div>
                
                <div class="form-group">
                    <label>Tipo:</label>
                    <select id="tipo" required>
                        <option value="">Selecciona tipo</option>
                        <option value="rodaje">Rodaje</option>
                        <option value="intervalos">Intervalos</option>
                        <option value="fuerza">Fuerza</option>
                        <option value="recuperacion">Recuperación</option>
                        <option value="test">Test</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Duración (HH:MM:SS):</label>
                    <input type="text" id="duracion" placeholder="00:08:00" required>
                </div>
                
                <div class="form-group">
                    <label>Potencia mínima %:</label>
                    <input type="number" id="potencia_min" step="0.01" min="0" max="100" placeholder="88" required>
                </div>
                
                <div class="form-group">
                    <label>Potencia máxima %:</label>
                    <input type="number" id="potencia_max" step="0.01" min="0" max="100" placeholder="94" required>
                </div>
                
                <div class="form-group">
                    <label>Pulso reserva %:</label>
                    <input type="number" id="pulso" step="0.01" min="0" max="100" placeholder="80" required>
                </div>
                
                <div class="form-group">
                    <label>Comentario:</label>
                    <textarea id="comentario" rows="2" placeholder="Comentarios adicionales"></textarea>
                </div>
                
                <button type="button" class="btn-crear-bloque" onclick="crearBloque()">✅ Crear Bloque</button>
            </form>
        </div>
        
        <!-- COLUMNA DERECHA: Asignar a Sesión -->
        <div class="columna">
            <h2>📋 Asignar a Sesión</h2>
            
            <form method="POST" action="{{ route('relaciones.store') }}" id="form-asignar">
                @csrf
                
                <!-- Opción: Sesión Existente o Nueva -->
                <div class="radio-group">
                    <label>
                        <input type="radio" name="tipo_sesion" value="existente" checked onclick="toggleSesionForm()">
                        Usar sesión existente
                    </label>
                    <label>
                        <input type="radio" name="tipo_sesion" value="nueva" onclick="toggleSesionForm()">
                        Crear nueva sesión
                    </label>
                </div>
                
                <!-- Seleccionar sesión existente -->
                <div id="sesion-existente">
                    <div class="form-group">
                        <label>Sesión existente:</label>
                        <select name="id_sesion_entrenamiento" id="id_sesion_entrenamiento">
                            <option value="">Selecciona una sesión</option>
                            @foreach($sesiones as $sesion)
                                <option value="{{ $sesion->id }}">{{ $sesion->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Crear nueva sesión -->
                <div id="sesion-nueva" class="hidden">
                    <div class="form-group">
                        <label>Nombre nueva sesión:</label>
                        <input type="text" name="nueva_sesion_nombre" id="nueva_sesion_nombre" placeholder="Ej: Sesión de montaña">
                    </div>
                    
                    <div class="form-group">
                        <label>Descripción:</label>
                        <textarea name="nueva_sesion_descripcion" rows="2" placeholder="Descripción de la sesión"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Plan:</label>
                        <select name="nueva_sesion_id_plan" id="nueva_sesion_id_plan">
                            <option value="">Selecciona un plan</option>
                            @foreach($planes as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Bloques Disponibles -->
                <div class="form-group">
                    <label>Bloques disponibles (selecciona múltiples):</label>
                    <select id="bloques-disponibles" name="bloques_ids[]" multiple class="form-control" style="height: 150px;">
                        @foreach($bloques as $bloque)
                            <option value="{{ $bloque->id }}">{{ $bloque->nombre }} ({{ $bloque->tipo }})</option>
                        @endforeach
                    </select>
                    <small>Ctrl+Click para seleccionar múltiples</small>
                </div>
                
                <div class="form-group">
                    <label>Repeticiones (para todos los seleccionados):</label>
                    <input type="number" name="repeticiones" min="1" value="1" required>
                </div>
                
                <button type="submit" class="btn-submit">✅ Asignar Bloques a Sesión</button>
            </form>
        </div>
    </div>
    
    <a href="/sesionbloque" class="back-link">← Volver a Sesiones</a>

    <script>
        function toggleSesionForm() {
            const tipo = document.querySelector('input[name="tipo_sesion"]:checked').value;
            const existente = document.getElementById('sesion-existente');
            const nueva = document.getElementById('sesion-nueva');
            
            if (tipo === 'existente') {
                existente.classList.remove('hidden');
                nueva.classList.add('hidden');
                document.getElementById('id_sesion_entrenamiento').required = true;
                document.getElementById('nueva_sesion_nombre').required = false;
                document.getElementById('nueva_sesion_id_plan').required = false;
            } else {
                existente.classList.add('hidden');
                nueva.classList.remove('hidden');
                document.getElementById('id_sesion_entrenamiento').required = false;
                document.getElementById('nueva_sesion_nombre').required = true;
                document.getElementById('nueva_sesion_id_plan').required = true;
            }
        }

        function crearBloque() {
            // Recoger datos del formulario
            const datos = {
                nombre: document.getElementById('nombre').value,
                descripcion: document.getElementById('descripcion').value,
                tipo: document.getElementById('tipo').value,
                duracion_estimada: document.getElementById('duracion').value,
                potencia_pct_min: document.getElementById('potencia_min').value,
                potencia_pct_max: document.getElementById('potencia_max').value,
                pulso_reserva_pct: document.getElementById('pulso').value,
                comentario: document.getElementById('comentario').value,
                _token: document.querySelector('meta[name="csrf-token"]').content
            };

            // Validar campos obligatorios
            if (!datos.nombre || !datos.tipo || !datos.duracion_estimada || 
                !datos.potencia_pct_min || !datos.potencia_pct_max || !datos.pulso_reserva_pct) {
                alert('⚠️ Por favor completa todos los campos obligatorios');
                return;
            }

            // Validar formato de duración
            const duracionRegex = /^([0-9]{2}):([0-5][0-9]):([0-5][0-9])$/;
            if (!duracionRegex.test(datos.duracion_estimada)) {
                alert('⚠️ La duración debe tener formato HH:MM:SS (ej: 00:08:00)');
                return;
            }

            // Enviar petición AJAX
            fetch('/api/bloques/crear-rapido', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(datos)
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('❌ Error: ' + data.error);
                } else {
                    // Mostrar mensaje de éxito
                    const mensaje = document.getElementById('mensaje-exito');
                    mensaje.style.display = 'block';
                    mensaje.textContent = '✅ Bloque "' + data.nombre + '" creado correctamente';
                    
                    // Limpiar formulario
                    document.getElementById('nombre').value = '';
                    document.getElementById('descripcion').value = '';
                    document.getElementById('tipo').value = '';
                    document.getElementById('duracion').value = '';
                    document.getElementById('potencia_min').value = '';
                    document.getElementById('potencia_max').value = '';
                    document.getElementById('pulso').value = '';
                    document.getElementById('comentario').value = '';
                    
                    // Añadir el nuevo bloque al select de la derecha
                    const select = document.getElementById('bloques-disponibles');
                    const option = document.createElement('option');
                    option.value = data.id;
                    option.textContent = data.nombre + ' (' + data.tipo + ')';
                    select.appendChild(option);
                    
                    // Seleccionar automáticamente el nuevo bloque
                    option.selected = true;
                    
                    // Ocultar mensaje después de 3 segundos
                    setTimeout(() => {
                        mensaje.style.display = 'none';
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('❌ Error al crear el bloque');
            });
        }

        // Inicializar
        toggleSesionForm();
    </script>
</body>
</html>