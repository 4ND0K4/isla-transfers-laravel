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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
     <!-- Icons FontAwesome -->
     <script src="https://kit.fontawesome.com/d80be3bccb.js" crossorigin="anonymous"></script>
    <!-- Vite -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <style>
        /* Estilos personalizados */
        .fixed-height {
            height: 400px; /* Ajusta la altura según tus necesidades */
        }
        .fixed-height img {
            object-fit: contain; /* Asegura que la imagen se ajuste correctamente */
            height: auto;     /* Mantiene la proporción de la imagen */
            width: 40%;      /* Ocupa todo el ancho del contenedor */
        }
    </style>
</head>
<body>
<main id="welcome">
    <div class="container text-center py-5">
        <div class="row">
            <div class="col-xl-4 fixed-height d-flex flex-column justify-content-center align-items-center mt-5">
                <h1 class="display-5 mt-4"> Isla Transfers</h1>
                <h2 class="fs-5 pt-2 pb-4">Tu aplicación para gestionar transfers</h2>
                <a href="#" id="openModal" class="btn btn-warning text-dark fw-bold text-decoration-none fs-6 pe-3">
                    Iniciar sesión
                </a>
            </div>

            <div class="col-xl-8 fixed-height">
                <img src="{{ asset('images/welcome.png') }}" class="img-fluid" alt="Señorita con maleta en el aeropuerto." />
            </div>
        </div>
    </div>

    <div class="container pt-5 mt-3">
        <div class="row text-center">
          <!-- Columna 1 -->
          <div class="col-md-4">
            <i class="fa-solid fa-car fs-1 mb-3"></i>
            <h3>Solicita un transfer</h3>
            <p>Preocupate tan solo de disfrutar de unas vacaciones de ensueño.</p>
          </div>
          <!-- Columna 2 -->
          <div class="col-md-4">
            <i class="fa-solid fa-hotel fs-1 mb-3"></i>
            <h3>Tu hotel ideal</h3>
            <p>Trabajamos solo con los mejores y más selectos alojamientos de la isla.</p>
          </div>
          <!-- Columna 3 -->
          <div class="col-md-4">
            <i class="fa-solid fa-map fs-1 mb-3"></i>
            <h3>Toma un tour</h3>
            <p>No te preocupes de nada más. Te acompañamos a que vivas una experiencia inolvidable.</p>
          </div>
        </div>
      </div>


</main>
<footer class="py-3">
    <div class="container d-flex justify-content-center align-items-center">
        <div class="col-6 d-flex justify-content-center">
            <ul class="list-unstyled d-flex gap-3 mb-0">
                <p>Contacta con nosotros a través de</p>
                <li><i class="fa-solid fa-envelope"></i></li>
                <li><i class="fa-brands fa-whatsapp"></i></li>
                <li><i class="fa-brands fa-telegram"></i></li>

            </ul>
        </div>
        <div class="col-6 d-flex justify-content-center">
            <ul class="list-unstyled d-flex gap-3 mb-0">
                <p>Siguenos en</p>
                <li><i class="fa-brands fa-wordpress"></i></li>
                <li><i class="fa-brands fa-facebook"></i></li>
                <li><i class="fa-brands fa-x-twitter"></i></li>
            </ul>
        </div>
    </div>
</footer>

<!-- Include the login modal component -->
@include('components.login_modal')

</body>
</html>

