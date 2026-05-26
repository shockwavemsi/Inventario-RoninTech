<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Ciclistas</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>

<h1>Registro de Ciclistas</h1>

<div class="register-container">

    @if ($errors->any())
        <div class="error-box">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <p class="success-msg">{{ session('success') }}</p>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf

        <div class="input-group">
            <label>Nombre:</label>
            <input type="text" name="nombre" required>
        </div>

        <div class="input-group">
            <label>Apellidos:</label>
            <input type="text" name="apellidos" required>
        </div>

        <div class="input-group">
            <label>Correo:</label>
            <input type="email" name="email" required>
        </div>

        <div class="input-group">
            <label>Contraseña:</label>
            <input type="password" name="password" required>
        </div>

        <div class="input-group">
            <label>Confirmar contraseña:</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <div class="input-group">
            <label>Fecha de nacimiento:</label>
            <input type="date" name="fecha_nacimiento" required>
        </div>

        <div class="input-group">
            <label>Peso (kg):</label>
            <input type="number" step="0.1" name="peso_base" required>
        </div>

        <div class="input-group">
            <label>Altura (cm):</label>
            <input type="number" name="altura_base" required>
        </div>

        <button type="submit">Registrarse</button>
    </form>

    <p class="login-link">
        <a href="{{ route('login') }}">Ya tengo cuenta, iniciar sesión</a>
    </p>

</div>

</body>
</html>