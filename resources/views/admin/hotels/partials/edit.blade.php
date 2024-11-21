<div class="modal fade" id="editHotelModal" tabindex="-1" aria-labelledby="editHotelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editHotelForm" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="editHotelModalLabel">Editar Hotel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editIdZona" class="form-label">Zona</label>
                    <select class="form-select" name="id_zona" id="editIdZona" required>
                        <option value="" disabled selected>Selecciona una zona</option>
                        <option value="1">Sur</option>
                        <option value="2">Norte</option>
                        <option value="3">Metropolitano</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="editComision" class="form-label">Comisión</label>
                    <input type="number" step="0.01" class="form-control" name="comision" id="editComision" required>
                </div>
                <div class="mb-3">
                    <label for="editUsuario" class="form-label">Usuario</label>
                    <input type="text" class="form-control" name="usuario" id="editUsuario" readonly>
                </div>
                <div class="mb-3">
                    <label for="editPassword" class="form-label">Contraseña (solo si desea cambiarla)</label>
                    <input type="password" class="form-control" name="password" id="editPassword">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Guardar</button>
            </div>
        </form>
    </div>
</div>
