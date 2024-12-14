<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Isla Transfers')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400..800&family=Caveat+Brush&family=Roboto+Flex:opsz@8..144&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Icons FontAwesome -->
    <script src="https://kit.fontawesome.com/d80be3bccb.js" crossorigin="anonymous"></script>

    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <script src="{{ asset('js/bundle.js') }}" defer></script>

</head>
<body>
    <nav class="navbar navbar-expand-xl bg-transparent">
        <div class="container-fluid">
            <a class="navbar-brand ps-5" href="#">
                <img src="{{ asset('images/icons/logo_app.png') }}" alt="Ícono" width="60" height="50">
            </a>
        </div>
        <ul class="nav nav-pills justify-content-end">
            <li>
                <!-- Enlace para abrir el modal -->
                <a href="#" id="openModal" class="text-dark fw-bold text-decoration-none fs-6 pe-3">
                    Iniciar sesión
                </a>
            </li>
        </div>
    </nav>

        @yield('content') <!-- Aquí se inyectará el contenido de las vistas -->

        !-- Include the login modal component -->
        @include('components.login_modal')
</body>
</html>
