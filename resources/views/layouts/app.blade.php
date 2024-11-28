<!DOCTYPE html>
<html lang="en">
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
      </style>
</head>
<body>
    <nav class="navbar navbar-expand-xl bg-white">
        <div class="container-fluid">
            <a class="navbar-brand ps-5" href="#">
                <img src="{{ asset('images/icons/logo_traveler.png') }}" alt="Ícono" width="150" height="50">
            </a>
        </div>
        <ul class="nav nav-pills justify-content-end">
            <!-- Enlace para abrir el modal -->
            <a href="#" id="openModal">Iniciar sesión</a>

        </div>
    </nav>

        @yield('content') <!-- Aquí se inyectará el contenido de las vistas -->

   <!-- Modal -->
    <div class="modal fade" id="fullScreenModal" tabindex="-1" aria-labelledby="fullScreenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="fullScreenModalLabel">Enlige tu tipo de sesión</h5>
          <button type="button" class="btn-close" id="closeModal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
            <div class="container d-flex flex-column align-items-center justify-content-center vh-100">
                <div class="row p-3">
                    <!-- Primera fila -->
                    <div class="col-xl-6 d-flex justify-content-center">
                        <div class="bg-warning-subtle p-3 text-center custom-container">
                            <a href="{{ route('traveler.login') }}">¿Eres cliente particular?</a>
                        </div>
                    </div>
                    <div class="col-xl-6 d-flex justify-content-center">
                        <div class="bg-success-subtle p-3 text-center custom-container">
                            <a href="{{ route('hotel.login') }}">¿Eres cliente corporativo?</a>
                        </div>
                    </div>
                </div>
                <div class="row p-3">
                    <!-- Segunda fila -->
                    <div class="col-xl-6 d-flex justify-content-center">
                        <div class="bg-info-subtle p-3 text-center custom-container">
                            <a href="{{ route('admin.login') }}">¿Eres administrador?</a>
                        </div>
                    </div>
                    <div class="col-xl-6 d-flex justify-content-center">
                        <div class="bg-light p-3 text-center custom-container">
                            <a href="#">¿Eres rider?</a>
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
