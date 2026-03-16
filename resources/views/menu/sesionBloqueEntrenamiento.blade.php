<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sesiones con Bloques</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vistas/sesionBloque.css') }}">
    
</head>
<body>
    <script src="{{ asset('js/menu.js') }}"></script>
    <div id="menu"></div>

<h1>Sesiones con Bloques</h1>
<script src="{{ asset('js/sesionBloque.js') }}"></script>
<a href="/sesionbloque/crear" class="btn-crear">+ Crear Nueva Sesión</a>
<div id="sesiones" class="loading">Cargando sesiones...</div>
</body>
</html>