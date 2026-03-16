<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Bloque</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/formularios/crearBloqueEntrenamiento.css') }}">
</head>
<body>
    <div class="container">
        <h1>Crear Nuevo Bloque de Entrenamiento</h1>
        
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('bloques.store') }}">
            @csrf
            
            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" 
                       value="{{ old('nombre') }}" 
                       placeholder="Ej: Sweet Spot 8 min" required>
            </div>
            
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <input type="text" id="descripcion" name="descripcion" 
                       value="{{ old('descripcion') }}" 
                       placeholder="Ej: Intervalos Sweet Spot">
            </div>
            
            <div class="form-group">
                <label for="tipo">Tipo *</label>
                <select id="tipo" name="tipo" required>
                    <option value="">Selecciona un tipo</option>
                    <option value="rodaje" {{ old('tipo') == 'rodaje' ? 'selected' : '' }}>Rodaje</option>
                    <option value="intervalos" {{ old('tipo') == 'intervalos' ? 'selected' : '' }}>Intervalos</option>
                    <option value="fuerza" {{ old('tipo') == 'fuerza' ? 'selected' : '' }}>Fuerza</option>
                    <option value="recuperacion" {{ old('tipo') == 'recuperacion' ? 'selected' : '' }}>Recuperación</option>
                    <option value="test" {{ old('tipo') == 'test' ? 'selected' : '' }}>Test</option>
                </select>
                <small>Valores permitidos: rodaje, intervalos, fuerza, recuperacion, test</small>
            </div>
            
            <div class="form-group">
                <label for="duracion_estimada">Duración estimada (HH:MM:SS) *</label>
                <input type="text" id="duracion_estimada" name="duracion_estimada" 
                       value="{{ old('duracion_estimada') }}" 
                       placeholder="00:08:00" required>
                <small>Formato: horas:minutos:segundos (ej: 01:30:00)</small>
            </div>
            
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="potencia_pct_min">Potencia mínima % *</label>
                        <input type="number" id="potencia_pct_min" name="potencia_pct_min" 
                               value="{{ old('potencia_pct_min') }}" 
                               step="0.01" min="0" max="100" placeholder="88" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="potencia_pct_max">Potencia máxima % *</label>
                        <input type="number" id="potencia_pct_max" name="potencia_pct_max" 
                               value="{{ old('potencia_pct_max') }}" 
                               step="0.01" min="0" max="100" placeholder="94" required>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="pulso_reserva_pct">Pulso reserva % *</label>
                <input type="number" id="pulso_reserva_pct" name="pulso_reserva_pct" 
                       value="{{ old('pulso_reserva_pct') }}" 
                       step="0.01" min="0" max="100" placeholder="80" required>
            </div>
            
            <div class="form-group">
                <label for="comentario">Comentario</label>
                <textarea id="comentario" name="comentario" rows="3" 
                          placeholder="Ej: Trabajo de umbral submáximo">{{ old('comentario') }}</textarea>
            </div>
            
            <button type="submit" class="btn">Crear Bloque</button>
        </form>
        
        <a href="/bloques" class="back-link">← Volver a la lista de bloques</a>
    </div>
</body>
</html>