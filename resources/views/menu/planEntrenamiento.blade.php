<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vistas/planEntrenamiento.css') }}">
    <title>Planes de Entrenamiento</title>
</head>

<body>
    <script src="{{ asset('js/menu.js') }}"></script>
    <div id="menu"></div>
    <h1>Planes de Entrenamiento</h1>

    <div id="planes"></div>
    <script src="{{ asset('js/planEntrenamiento.js') }}"></script>
</body>

</html>