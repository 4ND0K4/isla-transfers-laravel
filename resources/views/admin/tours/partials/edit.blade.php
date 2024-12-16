<div class="modal fade" id="editTourModal" tabindex="-1" aria-labelledby="editTourModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editTourForm" method="POST" class="modal-content">
            @csrf
            @method('PUT') <!-- Indica que el formulario realiza una solicitud PUT -->
            <div class="modal-header">
                <h5 class="modal-title" id="editTourModalLabel">Editar Excursión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if ($errors->any())
                    <div class="alert alert-danger" id="error-messages">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success" id="success-message">
                        {{ session('success') }}
                    </div>
                @endif
                <!-- Campos del formulario -->
                <div class="form-floating mb-3">
                    <input type="date" name="fecha_excursion" class="form-control" id="editFecha" required>
                    <label for="editFecha">Fecha</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="time" name="hora_entrada_excursion" class="form-control" id="editHoraEntrada" required>
                    <label for="editHoraEntrada">Hora ida</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="time" name="hora_salida_excursion" class="form-control" id="editHoraSalida" required>
                    <label for="editHoraSalida">Hora vuelta</label>
                </div>
                <div class="form-floating mb-3">
                    <select name="descripcion" class="form-select" id="editDescripcion" required>
                        <option value="" disabled selected>Selecciona una excursión</option>
                        <option value="Excursión a la Playa">Excursión a la Playa</option>
                        <option value="Visita al Volcán">Visita al Volcán</option>
                        <option value="Recorrido por la Ciudad">Recorrido por la Ciudad</option>
                        <option value="Aventura en la Selva">Aventura en la Selva</option>
                        <option value="Tour Cultural">Tour Cultural</option>
                        <option value="Paseo en Barco">Paseo en Barco</option>
                    </select>
                    <label for="editDescripcion">Descripción</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" name="num_excursionistas" class="form-control" id="editNumExcursionistas" required>
                    <label for="editNumExcursionistas">Número de Excursionistas</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" name="email_cliente" class="form-control" id="editEmailCliente" required>
                    <label for="editEmailCliente">Email Cliente</label>
                </div>
                <div class="form-floating mb-3">
                    <select name="id_hotel" class="form-select" id="editHotel" required>
                        <option value="" disabled selected>Selecciona el hotel de recogida</option>
                        @foreach ($hotels as $hotel)
                            <option value="{{ $hotel->id_hotel }}">{{ $hotel->id_hotel }}</option>
                        @endforeach
                    </select>
                    <label for="editHotel">Hotel</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" name="id_vehiculo" class="form-control" id="editVehiculo">
                    <label for="editVehiculo">Vehículo</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning text-white">Guardar Cambios</button>
                <div id="loadingSpinnerEdit" style="display: none;">
                    <div class="spinner-border text-secondary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editTourForm = document.querySelector('#editTourForm');
        const editTourButton = document.querySelector('#editTourForm button[type="submit"]');
        const loadingSpinnerEdit = document.getElementById('loadingSpinnerEdit');

        editTourForm.addEventListener('submit', function () {
            loadingSpinnerEdit.style.display = 'block';
            editTourButton.disabled = true;
        });
    });
</script>
