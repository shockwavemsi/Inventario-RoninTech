<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login Ciclistas</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

<h1>Login Ciclistas</h1>

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

    <p class="register-link">
        <a href="{{ route('register') }}">Registrarse</a>
    </p>

</div>

</body>

</html>