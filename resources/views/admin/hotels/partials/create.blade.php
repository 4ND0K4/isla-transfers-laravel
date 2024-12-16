<div class="modal fade" id="createHotelModal" tabindex="-1" aria-labelledby="createHotelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.hotels.store') }}" method="POST" class="modal-content" id="createHotelForm">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="createHotelModalLabel">Nuevo Hotel</h5>
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
                    <label for="idZona" class="form-label">Zona</label>
                    <select class="form-select" name="id_zona" id="idZona" required>
                        <option value="" disabled selected>Selecciona una zona</option>
                        <option value="1">Sur</option>
                        <option value="2">Norte</option>
                        <option value="3">Metropolitano</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="comision" class="form-label">Comisión</label>
                    <input type="number" step="0.1" class="form-control" name="comision" id="comision" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success text-white" id="createSubmitButton">
                    Crear
                    <div id="createLoadingSpinner" class="spinner-border spinner-border-sm text-light ms-2" role="status" style="display: none;">
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
        document.getElementById('createHotelForm').addEventListener('submit', function() {
        document.getElementById('createSubmitButton').disabled = true;
        document.getElementById('createLoadingSpinner').style.display = 'inline-block';
    });
</script>
