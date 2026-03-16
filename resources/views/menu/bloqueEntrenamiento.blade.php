<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Bloques del Ciclista</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vistas/bloqueEntrenamiento.css') }}">
</head>

<body>
    <form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">Cerrar sesión</button>
</form>
    <script src="{{ asset('js/menu.js') }}"></script>
    <div id="menu"></div>


    <h1>Bloques de entrenamiento</h1>
    <script src="{{ asset('js/bloqueEntrenamiento.js') }}"></script>
    <div id="bloques"></div>

</body>

</html>