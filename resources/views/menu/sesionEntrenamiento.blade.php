<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Sesiones</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        
    </style>
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vistas/sesionEntrenamiento.css') }}">
</head>

<body>
    <script src="{{ asset('js/menu.js') }}"></script>
    <div id="menu"></div>

    <h1>Sesiones de Entrenamiento</h1>

    <div id="sesiones"></div>
    <script src="{{ asset('js/sesionEntrenamiento.js') }}"></script>

</body>

</html>