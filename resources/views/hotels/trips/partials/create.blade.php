<div class="modal fade" id="createTourModal" tabindex="-1" aria-labelledby="createTourModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('hotel.tours.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Nueva Excursión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Campos del formulario -->
                <div class="form-floating mb-3">
                    <input type="date" name="fecha_excursion" class="form-control" id="createFecha" required>
                    <label for="createFecha">Fecha</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="time" name="hora_entrada_excursion" class="form-control" id="createHoraEntrada" required>
                    <label for="createHoraEntrada">Hora ida</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="time" name="hora_salida_excursion" class="form-control" id="createHoraSalida" required>
                    <label for="createHoraSalida">Hora vuelta</label>
                </div>
                <div class="form-floating mb-3">
                    <select name="descripcion" class="form-select" id="createDescripcion" required>
                        <option value="" disabled selected>Selecciona una excursión</option>
                        <option value="Excursión a la Playa">Excursión a la Playa</option>
                        <option value="Visita al Volcán">Visita al Volcán</option>
                        <option value="Recorrido por la Ciudad">Recorrido por la Ciudad</option>
                        <option value="Aventura en la Selva">Aventura en la Selva</option>
                        <option value="Tour Cultural">Tour Cultural</option>
                        <option value="Paseo en Barco">Paseo en Barco</option>
                    </select>
                    <label for="createDescripcion">Descripción</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" name="num_excursionistas" class="form-control" id="createNumExcursionistas" required>
                    <label for="createNumExcursionistas">Número de Excursionistas</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" name="email_cliente" class="form-control" id="createEmailCliente" required>
                    <label for="createEmailCliente">Email Cliente</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" name="id_vehiculo" class="form-control" id="createVehiculo">
                    <label for="createVehiculo">Vehículo</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success text-white">
                    Crear
                    <div id="loadingSpinnerCreate" class="spinner-border spinner-border-sm text-light ms-2" role="status" style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const createTourForm = document.querySelector('#createTourModal form');
        const createTourButton = document.querySelector('#createTourModal button[type="submit"]');
        const loadingSpinnerCreate = document.getElementById('loadingSpinnerCreate');

        createTourForm.addEventListener('submit', function () {
            loadingSpinnerCreate.style.display = 'inline-block';
            createTourButton.disabled = true;
        });
    });
</script>
