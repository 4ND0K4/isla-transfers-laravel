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
        /* Animación personalizada para abrir y cerrar */
        .modal.fade.custom-modal {
          opacity: 0; /* Estado inicial (invisible) */
          transition: opacity 1s ease; /* Animación de apertura y cierre */
        }
        .modal.show.custom-modal {
          opacity: 1; /* Estado visible */
        }
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
            <h3>Título 1</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
          </div>
          <!-- Columna 2 -->
          <div class="col-md-4">
            <i class="bi bi-emoji-heart-eyes-fill fs-1 mb-3"></i>
            <h3>Título 2</h3>
            <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
          </div>
          <!-- Columna 3 -->
          <div class="col-md-4">
            <i class="bi bi-emoji-heart-eyes-fill fs-1 mb-3"></i>
            <h3>Título 3</h3>
            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
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

 <!-- Modal -->
 <div class="modal fade" id="fullScreenModal" tabindex="-1" aria-labelledby="fullScreenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">
        <div class="modal-body" id="welcome">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" id="closeModal" aria-label="Cerrar"></button>
            <div class="container d-flex flex-column align-items-center justify-content-center vh-100">
                <div class="row p-3">
                    <!-- Primera fila -->
                    <div class="col-xl-6 d-flex justify-content-center">
                        <div class="bg-transparent p-3 text-center custom-container">
                            <a href="{{ route('traveler.login') }}" class="fs-3 custom-hover">¿Eres cliente particular?</a>
                        </div>
                    </div>
                    <div class="col-xl-6 d-flex justify-content-center">
                        <div class="bg-transparent p-3 text-center custom-container">
                            <a href="{{ route('hotel.login') }}" class="fs-3 custom-hover">¿Eres cliente corporativo?</a>
                        </div>
                    </div>
                </div>
                <div class="row p-3">
                    <!-- Segunda fila -->
                    <div class="col-xl-6 d-flex justify-content-center">
                        <div class="bg-transparent p-3 text-center custom-container">
                            <a href="{{ route('admin.login') }}" class="fs-3 custom-hover">¿Eres administrador?</a>
                        </div>
                    </div>
                    <div class="col-xl-6 d-flex justify-content-center">
                        <div class="bg-transparent p-3 text-center custom-container">
                            <a href="#" class="fs-3 custom-hover">¿Organizas tours?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
    </div>
<!-- Agregar JS de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Seleccionamos los elementos
    const modalElement = document.getElementById('fullScreenModal');
    const modalInstance = new bootstrap.Modal(modalElement); // Inicializamos el modal
    const openModalButton = document.getElementById('openModal');
    const closeModalButton = document.getElementById('closeModal');

    // Función para abrir el modal lentamente
    openModalButton.addEventListener('click', (e) => {
      e.preventDefault(); // Evita que el enlace recargue la página
      modalElement.classList.add('fade', 'custom-modal'); // Aseguramos que la animación esté aplicada
      modalInstance.show(); // Mostramos el modal
    });

    // Función para cerrar el modal lentamente
    closeModalButton.addEventListener('click', () => {
      modalElement.addEventListener('transitionend', () => {
        modalInstance.hide(); // Ocultamos el modal después de la transición
      }, { once: true }); // Se ejecuta solo una vez
      modalElement.classList.remove('show'); // Oculta el contenido lentamente
    });
</script>
</body>
</html>

