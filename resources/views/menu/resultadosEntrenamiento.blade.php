<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Resultados de Entrenamiento</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vistas/resultados.css') }}">
</head>
<body>

    <script src="{{ asset('js/menu.js') }}"></script>
    <div id="menu"></div>

        <h1>Mis Resultados de Entrenamiento</h1>

    <a href="{{ route('resultado.crear') }}" class="btn-crear">➕ Nuevo Resultado</a>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if($resultados->isEmpty())
        <div class="sin-datos">
            <p style="font-size: 18px;">📊 No hay resultados registrados</p>
            <p>Comienza registrando tu primer entrenamiento</p>
            <a href="{{ route('resultado.crear') }}" style="color: #007bff;">→ Crear resultado</a>
        </div>
    @else
        <div id="resultados-lista">
            @foreach($resultados as $r)
                <div class="resultado-item">
                    <!-- Cabecera clickable - AHORA MUESTRA NOMBRE DE SESIÓN -->
                    <div class="resultado-header" onclick="toggleDetalle({{ $r->id }})">
                        <div class="resultado-info">
                            <span><strong>📅</strong> {{ \Carbon\Carbon::parse($r->fecha)->format('d/m/Y') }}</span>
                            
                            <!-- ✅ NUEVO: Mostrar nombre de la sesión -->
                            @if($r->id_sesion)
                                <span class="nombre-sesion">
                                    <strong>📋</strong> {{ $r->sesion->nombre ?? 'Sesión #' . $r->id_sesion }}
                                </span>
                            @else
                                <span><strong>📋</strong> Entreno libre</span>
                            @endif
                            
                            <span><strong>🚴</strong> {{ $r->bicicleta->nombre ?? 'Sin bici' }}</span>
                            <span><strong>⏱️</strong> {{ $r->duracion }}</span>
                            <span><strong>📏</strong> {{ $r->kilometros }} km</span>
                        </div>
                        <span class="toggle-icon" id="icon-{{ $r->id }}">▼</span>
                    </div>

                    <!-- Detalles que se cargarán vía API -->
                    <div class="resultado-detalle" id="detalle-{{ $r->id }}">
                        <div class="loading">Cargando detalles...</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 20px; color: #666; font-size: 14px;">
            Total: {{ $resultados->count() }} entrenamientos
        </div>
    @endif

    <script src="{{ asset('js/resultados.js') }}"></script>
</body>
</html>