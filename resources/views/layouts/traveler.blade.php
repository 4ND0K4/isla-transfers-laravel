<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Isla Transfers')</title>
    <meta name="author" content="PHPOWER" />
    <meta name="description" content="La página de inicio del panel de Clientes particulares (Viajeros) de Isla Transfer
    es accesible cuando el usuario viajero se identifica con sus credenciales. Desde aquí se puede acceder
    a la gestión de todas las acciones disponibles en la aplicación web para este tipo de usuario" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400..800&family=Caveat+Brush&family=Roboto+Flex:opsz@8..144&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Icons Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
     <!-- Icons FontAwesome -->
     <script src="https://kit.fontawesome.com/d80be3bccb.js" crossorigin="anonymous"></script>
     <!-- Estilos -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    @vite(['resources/js/error.js'])
    <!-- Calendario -->
    @include ('travelers.components.calendar')
</head>
<body  id="traveler">

    @include('travelers.components.navbar')

    <main class="container-fluid px-0">
        @yield('content') <!-- Aquí se inyectará el contenido de las vistas -->
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
