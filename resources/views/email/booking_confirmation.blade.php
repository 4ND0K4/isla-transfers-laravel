<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Reserva</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <h1>Confirmación de su Reserva</h1>
    <p>¡Gracias por reservar con Isla Transfer! A continuación le presentamos los detalles de su reserva:</p>
    <ul>
        <li><strong>Localizador:</strong> {{ $localizador }}</li>
        <li><strong>Tipo de Reserva:</strong> {{ $tipo_reserva }}</li>
        <li><strong>Fecha:</strong> {{ $fecha }}</li>
        <li><strong>Hora:</strong> {{ $hora }}</li>
        <li><strong>Hotel:</strong> {{ $hotel }}</li>
        <li><strong>Origen de Vuelo:</strong> {{ $origen_vuelo }}</li>
        <li><strong>Número de Vuelo:</strong> {{ $numero_vuelo }}</li>
        <li><strong>Número de Viajeros:</strong> {{ $num_viajeros }}</li>
    </ul>
    <p>Gracias por confiar en Isla Transfer. Si tiene alguna pregunta, no dude en contactarnos.</p>
</body>
</html>
