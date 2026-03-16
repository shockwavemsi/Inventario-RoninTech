<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
</head>
<body>
<h1>Bienvenido {{ Auth::user()->nombre }}</h1>

<ul id="menu"></ul>

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">Cerrar sesión</button>
</form>

<script src="{{ asset('js/menu.js') }}"></script>
</body>
</html>
