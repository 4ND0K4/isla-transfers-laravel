<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Administración</title>
    <meta name="author" content="PHPOWER" />
    <meta name="description" content="La página de inicio del panel de administración de Isla Transfer
    es accesible cuando el administrador se identifica con sus credenciales. Desde aquí se puede acceder
    a la gestión de todas las acciones disponibles en la aplicación web" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Flex:opsz@8..144&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Icons FontAwesome -->
    <script src="https://kit.fontawesome.com/d80be3bccb.js" crossorigin="anonymous"></script>
    <!-- Enlaces Hojas Estilo -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <!-- Implantación del calendario -->
    @include ('admin.components.calendar')
</head>
<!-- ///////////////////////////////////////////////// body //////////////////////////////////////////////// -->
<body id="admin">
    <!-- Sidebar -->
    @include('admin.components.sidebar')
    <!-- Contenido Principal -->
    <main class="container-fluid px-0">
        @yield('content')
    </main>
    <!-- Abrir modales en Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
