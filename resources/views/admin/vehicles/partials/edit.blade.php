<div class="modal fade" id="editVehicleModal" tabindex="-1" aria-labelledby="editVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editVehicleForm" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="editVehicleModalLabel">Editar Vehículo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editDescripcion" class="form-label">Descripción</label>
                    <input type="text" class="form-control" name="descripcion" id="editDescripcion" required>
                </div>
                <div class="mb-3">
                    <label for="editEmailConductor" class="form-label">Email Conductor</label>
                    <input type="email" class="form-control" name="email_conductor" id="editEmailConductor" required>
                </div>
                <div class="mb-3">
                    <label for="editPassword" class="form-label">Contraseña (solo si desea cambiarla)</label>
                    <input type="password" class="form-control" name="password" id="editPassword">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning text-white">Guardar Cambios</button>
                <div id="loadingSpinner" style="display: none;">
                    <div class="spinner-border text-secondary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
