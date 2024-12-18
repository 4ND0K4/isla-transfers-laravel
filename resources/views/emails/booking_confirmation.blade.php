<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Reserva</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <div class="card">
            <div class="card-header text-center">
                <img src="cid:logo_img" alt="Isla Transfer Logo" class="img-fluid" style="max-width: 120px;">
            </div>
            <div class="card-body">
                <h1 class="card-title text-center">Detalles de su Reserva</h1>
                <p class="card-text text-center">¡Gracias por reservar con Isla Transfer! A continuación le presentamos los detalles de su reserva:</p>
                <div class="bg-light p-3 rounded">
                    <p><strong>Localizador:</strong> {{ $localizador }}</p>
                    <p><strong>Trayecto:</strong> {{ $tipoReservaTexto }}</p>
                    <p><strong>Hotel:</strong> {{ $datosReserva['id_hotel'] }}</p>
                    <p><strong>Fecha:</strong> {{ $fecha }}</p>
                    <p><strong>Hora:</strong> {{ $hora }}</p>
                    <p><strong>Origen de vuelo:</strong> {{ $datosReserva['origen_vuelo_entrada'] }}</p>
                    <p><strong>Número de vuelo:</strong> {{ $datosReserva['numero_vuelo_entrada'] }}</p>
                    <p><strong>Número de viajeros:</strong> {{ $datosReserva['num_viajeros'] }}</p>
                </div>
            </div>
            <div class="card-footer text-center">
                <p>Gracias por confiar en Isla Transfer. Si tiene alguna pregunta o necesita ayuda, no dude en contactarnos.</p>
                <p class="text-muted">&copy; 2024 Isla Transfer. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>

</body>
</html>




