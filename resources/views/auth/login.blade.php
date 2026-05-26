<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login Inventario</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

<h1>Login Inventario</h1>

<div class="login-container">

    @if(session('error'))
        <p class="error-msg">{{ session('error') }}</p>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div>
            <label>Correo:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Contraseña:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Ingresar</button>
    </form>

</div>

</body>

</html>
