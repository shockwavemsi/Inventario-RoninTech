<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vistas/ciclista.css') }}">
    <title>Ciclista</title>
</head>
<body>
    <script src="{{ asset('js/menu.js') }}"></script>
    <div id="menu"></div>

    <h2>Datos del Ciclista</h2>
    <div id="datos-ciclista"></div>

    <h2>Histórico del Ciclista</h2>
    <script src="{{ asset('js/ciclista.js') }}"></script>
    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Peso</th>
                <th>FTP</th>
                <th>Pulso Máx</th>
                <th>Pulso Reposo</th>
                <th>Potencia Máx</th>
                <th>Grasa %</th>
                <th>VO2max</th>
                <th>Comentario</th>
            </tr>
        </thead>
        <tbody id="tabla-historico"></tbody>
    </table>

</body>


</html>