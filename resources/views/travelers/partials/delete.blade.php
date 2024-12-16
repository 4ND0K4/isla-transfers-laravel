<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="confirmarEliminacionModal" tabindex="-1" aria-labelledby="confirmarEliminacionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning-subtle">
                <h2 class="modal-title" id="confirmarEliminacionLabel">Confirmar Eliminación</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                ¿Estás seguro de que deseas eliminar esta reserva?
                <div id="deleteErrorMessages" class="alert alert-danger mt-3" style="display: none;"></div>
                <div id="deleteSuccessMessage" class="alert alert-success mt-3" style="display: none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btnEliminar" class="btn btn-danger" data-url="">
                    Eliminar
                    <div id="deleteLoadingSpinner" class="spinner-border spinner-border-sm text-light ms-2" role="status" style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('btnEliminar').addEventListener('click', function () {
        document.getElementById('deleteLoadingSpinner').style.display = 'inline-block';
        this.disabled = true;
    });
</script>
