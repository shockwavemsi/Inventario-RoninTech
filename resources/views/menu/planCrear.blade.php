<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        
    </style>
</head>

<body>
    <div class="container">
        <h1>Crear Nuevo Plan</h1>

        <form action="{{ route('plan.store') }}" method="POST">
            @csrf

            <label>Nombre:</label>
            <input type="text" name="nombre" required>

            <label>Descripción:</label>
            <input type="text" name="descripcion" required>

            <label>Fecha inicio:</label>
            <input type="date" name="fecha_inicio" required>

            <label>Fecha fin:</label>
            <input type="date" name="fecha_fin" required>

            <label>Objetivo:</label>
            <input type="text" name="objetivo" required>

            <label>Activo:</label>
            <select name="activo">
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>

            <button type="submit">Crear plan</button>
        </form>

        <a href="/plan">← Ir a Planes</a>
    </div>
</body>
<a href="/bienvenida">Volver al menu</a>
</body>

</html>