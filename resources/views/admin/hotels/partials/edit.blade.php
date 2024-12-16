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
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
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
                    <input type="text" class="form-control" name="usuario" id="editUsuario">
                </div>
                <div class="mb-3">
                    <label for="editPassword" class="form-label">Contraseña (solo si desea cambiarla)</label>
                    <input type="password" class="form-control" name="password" id="editPassword">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning text-white" id="editSubmitButton">
                    Guardar Cambios
                    <div id="editLoadingSpinner" class="spinner-border spinner-border-sm text-light ms-2" role="status" style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </button>
                <div id="loadingSpinner" style="display: none;">
                    <div class="spinner-border text-secondary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
        document.getElementById('editHotelForm').addEventListener('submit', function() {
        document.getElementById('editSubmitButton').disabled = true;
        document.getElementById('editLoadingSpinner').style.display = 'inline-block';
    });
</script>
