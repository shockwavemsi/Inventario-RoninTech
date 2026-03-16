<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crear Sesión</title>
    <style>
        /* ======== ESTILOS GENERALES ======== */
        body {
            font-family: "Poppins", Arial, sans-serif;
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background: #f4f6f9;
            color: #333;
        }

        /* ======== CONTENEDOR DEL FORMULARIO ======== */
        form {
            background: #fff;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        /* ======== TÍTULO ======== */
        h1 {
            text-align: center;
            color: #1a73e8;
            margin-bottom: 25px;
            font-size: 1.6rem;
        }

        /* ======== LABELS ======== */
        label {
            font-weight: 600;
            color: #444;
            margin-bottom: 4px;
        }

        /* ======== INPUTS Y SELECTS ======== */
        input,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #d0d7e2;
            border-radius: 8px;
            font-size: 1rem;
            background: #fafbfc;
            transition: border 0.2s ease, box-shadow 0.2s ease;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #1a73e8;
            box-shadow: 0 0 6px rgba(26, 115, 232, 0.3);
        }

        /* ======== BOTÓN ======== */
        button {
            background: #1a73e8;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.2s ease, transform 0.1s ease;
        }

        button:hover {
            background: #155fc4;
            transform: scale(1.02);
        }

        /* ======== ENLACE VOLVER ======== */
        a {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #1a73e8;
            font-weight: 600;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <h1>Crear nueva sesión</h1>

    <form action="{{ route('sesion.store') }}" method="POST">
        @csrf

        <label>Plan asociado:</label>
        <select name="id_plan" required>
            @foreach($planes as $plan)
                <option value="{{ $plan->id }}">{{ $plan->nombre }}</option>
            @endforeach
        </select>

        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <label>Fecha:</label>
        <input type="date" name="fecha" required>

        <label>Descripción:</label>
        <input type="text" name="descripcion" required>

        <label>Completada:</label>
        <select name="completada">
            <option value="0">No</option>
            <option value="1">Sí</option>
        </select>

        <button type="submit">Crear sesión</button>
        <a href="/sesion">Ir a Sesiones →</a>
    </form>
    <a href="/bienvenida">← Volver al menú</a>

</body>

</html>