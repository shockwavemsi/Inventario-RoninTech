<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Resultado de Entrenamiento</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/formularios/crearResultado.css') }}">
</head>
<body>
    <!-- Notificación flotante -->
    <div id="notification" class="notification">
        ⚠️ Por favor completa todos los campos obligatorios
    </div>

    <div class="container">
        <h1>Registrar Resultado de Entrenamiento</h1>
        
        @if(session('success'))
            <div class="alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-error">
                ❌ {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                <strong>❌ Errores:</strong>
                <ul style="margin: 10px 0 0 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('resultado.guardar') }}" id="resultadoForm">
            @csrf
            
            <!-- Campo: Sesión realizada -->
            <div class="form-group">
                <label for="id_sesion" class="required-field">Sesión realizada</label>
                <select name="id_sesion" id="id_sesion" class="required" required>
                    <option value="">Selecciona la sesión que completaste</option>
                    @foreach($sesiones as $sesion)
                        <option value="{{ $sesion->id }}" {{ old('id_sesion') == $sesion->id ? 'selected' : '' }}>
                            {{ $sesion->nombre }} - {{ \Carbon\Carbon::parse($sesion->fecha)->format('d/m/Y') }}
                        </option>
                    @endforeach
                </select>
                <div class="error-message" id="error-id_sesion">Debes seleccionar una sesión</div>
            </div>
            
            <!-- Campo: Bicicleta -->
            <div class="form-group">
                <label for="id_bicicleta" class="required-field">Bicicleta</label>
                <select name="id_bicicleta" id="id_bicicleta" class="required" required>
                    <option value="">Selecciona una bicicleta</option>
                    @foreach($bicicletas as $bici)
                        <option value="{{ $bici->id }}" {{ old('id_bicicleta') == $bici->id ? 'selected' : '' }}>
                            {{ $bici->nombre }} ({{ $bici->tipo }})
                        </option>
                    @endforeach
                </select>
                <div class="error-message" id="error-id_bicicleta">Debes seleccionar una bicicleta</div>
            </div>
            
            <!-- FECHA Y HORA SEPARADOS -->
            <div class="row">
                <div class="col">
                    <label for="fecha" class="required-field">Fecha</label>
                    <input type="date" name="fecha" id="fecha" class="required" value="{{ old('fecha') }}" required>
                    <div class="error-message" id="error-fecha">Debes indicar la fecha</div>
                </div>
                <div class="col">
                    <label for="hora" class="required-field">Hora</label>
                    <input type="time" name="hora" id="hora" class="required" value="{{ old('hora') }}" required>
                    <div class="error-message" id="error-hora">Debes indicar la hora</div>
                </div>
            </div>
            
            <!-- Duración (solo horas y minutos) y Kilómetros -->
            <div class="row">
                <div class="col">
                    <label for="duracion" class="required-field">Duración (HH:MM)</label>
                    <input type="time" name="duracion" id="duracion" class="required" step="60" value="{{ old('duracion') }}" required>
                    <small>Formato: horas:minutos</small>
                    <div class="error-message" id="error-duracion">Debes indicar la duración</div>
                </div>
                <div class="col">
                    <label for="kilometros" class="required-field">Kilómetros</label>
                    <input type="number" name="kilometros" id="kilometros" class="required" 
                           step="0.01" min="0" value="{{ old('kilometros', '0.00') }}" 
                           placeholder="0.00" required>
                    <small>Distancia en kilómetros (ej: 25.5)</small>
                    <div class="error-message" id="error-kilometros">Debes indicar los kilómetros</div>
                </div>
            </div>
            
            <!-- Recorrido - OBLIGATORIO -->
            <div class="form-group">
                <label for="recorrido" class="required-field">Recorrido</label>
                <input type="text" name="recorrido" id="recorrido" class="required" value="{{ old('recorrido') }}" placeholder="Ej: Ruta Valle" required>
                <small>Describe la ruta realizada</small>
                <div class="error-message" id="error-recorrido">Debes describir el recorrido</div>
            </div>
            
            <h3 style="margin-top: 30px;">Estadísticas</h3>
            
            <!-- Pulsaciones (opcionales) -->
            <div class="row">
                <div class="col">
                    <label for="pulso_medio">Pulso medio (opcional)</label>
                    <input type="number" name="pulso_medio" id="pulso_medio" value="{{ old('pulso_medio') }}" placeholder="ppm">
                </div>
                <div class="col">
                    <label for="pulso_max">Pulso máximo (opcional)</label>
                    <input type="number" name="pulso_max" id="pulso_max" value="{{ old('pulso_max') }}" placeholder="ppm">
                </div>
            </div>
            
            <!-- Potencias -->
            <div class="row">
                <div class="col">
                    <label for="potencia_media">Potencia media (opcional)</label>
                    <input type="number" name="potencia_media" id="potencia_media" value="{{ old('potencia_media') }}" placeholder="vatios">
                </div>
                <div class="col">
                    <label for="potencia_normalizada" class="required-field">Potencia normalizada</label>
                    <input type="number" name="potencia_normalizada" id="potencia_normalizada" class="required" value="{{ old('potencia_normalizada') }}" placeholder="vatios" required>
                    <small>Campo obligatorio</small>
                    <div class="error-message" id="error-potencia_normalizada">Debes indicar la potencia normalizada</div>
                </div>
            </div>
            
            <!-- Velocidad - AHORA OBLIGATORIO -->
            <div class="row">
                <div class="col">
                    <label for="velocidad_media" class="required-field">Velocidad media</label>
                    <input type="number" name="velocidad_media" id="velocidad_media" class="required" step="0.1" value="{{ old('velocidad_media') }}" placeholder="km/h" required>
                    <small>Campo obligatorio</small>
                    <div class="error-message" id="error-velocidad_media">Debes indicar la velocidad media</div>
                </div>
                <div class="col">
                    <!-- Espacio vacío -->
                </div>
            </div>
            
            <!-- Comentario (opcional) -->
            <div class="form-group">
                <label for="comentario">Comentario (opcional)</label>
                <textarea name="comentario" id="comentario" rows="3" placeholder="Observaciones adicionales">{{ old('comentario') }}</textarea>
            </div>
            
            <button type="submit" class="btn-submit" id="submitBtn">✅ Guardar Resultado</button>
        </form>
        
        <a href="{{ route('resultado.lista') }}" class="btn-secondary">← Volver a Resultados</a>
    </div>

    <script src="{{ asset('js/crearResultado.js') }}"></script>
        
</body>
</html>