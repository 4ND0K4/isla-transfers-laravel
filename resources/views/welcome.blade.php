<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Isla Transfers')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Flex:opsz@8..144&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    <style>
        /* Estilos personalizados */
        .fixed-height {
            height: 650px; /* Ajusta la altura según tus necesidades */
        }
        .fixed-height img {
            object-fit: cover; /* Asegura que la imagen se ajuste correctamente */
            height: 100%;     /* Ocupa toda la altura del contenedor */
            width: 100%;      /* Opcional, para asegurar el ancho completo */
        }
    </style>
</head>
<body>
<main id="welcome">
    <div class="container text-center py-3">
        <div class="row">
            <div class="col-xl-4 fixed-height d-flex flex-column justify-content-center align-items-center">
                <h1 class="display-5"> Isla Transfers</h1>
                <h2 class="fs-5">Tu aplicación para gestionar transfers</h2>
                <a href="#" id="openModal" class="text-dark fw-bold text-decoration-none fs-6 pe-3">
                    Iniciar sesión
                </a>
            </div>

            <div class="col-xl-8 fixed-height">
                <img src="{{ asset('images/logo_alt2.png') }}" class="img-fluid" alt="Ícono">
            </div>
        </div>
    </div>



    <div class="container my-5">
        <div class="row text-center bg-white">
          <!-- Columna 1 -->
          <div class="col-md-4">
            <i class="bi bi-emoji-heart-eyes-fill fs-1 mb-3"></i>
            <h3>Del aeropuerto al hotel</h3>
            <p>Un conductor te llevará al hotel de tus vacaciones con tan solo solicitar un trayecto.</p>
          </div>
          <!-- Columna 2 -->
          <div class="col-md-4">
            <i class="bi bi-emoji-heart-eyes-fill fs-1 mb-3"></i>
            <h3>Del hotel al aeropuerto</h3>
            <p>¿Se acabaron las vacaciones? Nosotros hacemos más ameno el trayecto de vuelta dejandote a la hora acordada.</p>
          </div>
          <!-- Columna 3 -->
          <div class="col-md-4">
            <i class="bi bi-emoji-heart-eyes-fill fs-1 mb-3"></i>
            <h3>Toma un tour</h3>
            <p>Y no te preocupes de los desplazamientos. Un conductor te llevará y recogerá a las horas acordadas.</p>
          </div>
        </div>
      </div>


</main>
<footer class="bg-white">
    <div class="container d-flex justify-content-center align-items-center py-3">
        <div class="col-6">
            <ul class="list-unstyled d-flex gap-3 mb-0">
                <p>Contacta con nosotros a través de</p>
                <li><i class="bi bi-envelope-at"></i></li>
                <li><i class="bi bi-whatsapp"></i></li>
                <li><i class="bi bi-telegram"></i></li>
            </ul>
        </div>
        <div class="col-6">
            <ul class="list-unstyled d-flex gap-3 mb-0">
                <p>Siguenos en</p>
                <li><i class="bi bi-facebook"></i></li>
                <li><i class="bi bi-twitter-x"></i></li>
                <li><i class="bi bi-threads"></i></li>
                <li><i class="bi bi-instagram"></i></li>
                <li><i class="bi bi-tiktok"></i></li>
                <li><i class="bi bi-youtube"></i></li>
            </ul>
        </div>
    </div>
</footer>

<!-- Include the login modal component -->
@include('components.login_modal')

</body>
</html>

