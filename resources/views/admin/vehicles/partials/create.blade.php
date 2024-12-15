<div class="modal fade" id="createVehicleModal" tabindex="-1" aria-labelledby="createVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.vehicles.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="createVehicleModalLabel">Nuevo Vehículo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <input type="text" class="form-control" name="descripcion" id="descripcion" required>
                </div>
                <div class="mb-3">
                    <label for="email_conductor" class="form-label">Email Conductor</label>
                    <input type="email" class="form-control" name="email_conductor" id="email_conductor" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success text-white">Crear</button>
                <div id="loadingSpinner" style="display: none;">
                    <div class="spinner-border text-secondary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
