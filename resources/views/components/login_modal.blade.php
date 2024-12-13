<!-- Estilos -->
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

<!-- Agregar JS -->
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
