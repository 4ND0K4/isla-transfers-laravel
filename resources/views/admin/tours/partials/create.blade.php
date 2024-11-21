<div class="modal fade" id="createTourModal" tabindex="-1" aria-labelledby="createTourModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.tours.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Nueva Excursión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('admin.tours.partials.form', ['action' => 'create'])
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-dark">Crear</button>
            </div>
        </form>
    </div>
</div>
