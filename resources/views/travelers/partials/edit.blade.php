<div class="modal fade" id="editBookingModal" tabindex="-1" role="dialog" aria-labelledby="editBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editBookingForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editBookingModalLabel">Editar Reserva</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="id_destino">Destino</label>
                        <input type="text" class="form-control" id="id_destino" name="id_destino" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_entrada">Fecha de Entrada</label>
                        <input type="date" class="form-control" id="fecha_entrada" name="fecha_entrada">
                    </div>
                    <div class="form-group">
                        <label for="hora_entrada">Hora de Entrada</label>
                        <input type="time" class="form-control" id="hora_entrada" name="hora_entrada">
                    </div>
                    <div class="form-group">
                        <label for="fecha_vuelo_salida">Fecha de Vuelo de Salida</label>
                        <input type="date" class="form-control" id="fecha_vuelo_salida" name="fecha_vuelo_salida">
                    </div>
                    <div class="form-group">
                        <label for="hora_vuelo_salida">Hora de Vuelo de Salida</label>
                        <input type="time" class="form-control" id="hora_vuelo_salida" name="hora_vuelo_salida">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#editBookingModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var destino = button.data('destino');
        var fechaEntrada = button.data('fecha-entrada');
        var horaEntrada = button.data('hora-entrada');
        var fechaVueloSalida = button.data('fecha-vuelo-salida');
        var horaVueloSalida = button.data('hora-vuelo-salida');

        var modal = $(this);
        modal.find('#id_destino').val(destino);
        modal.find('#fecha_entrada').val(fechaEntrada);
        modal.find('#hora_entrada').val(horaEntrada);
        modal.find('#fecha_vuelo_salida').val(fechaVueloSalida);
        modal.find('#hora_vuelo_salida').val(horaVueloSalida);

        var form = modal.find('#editBookingForm');
        form.attr('action', '/traveler/bookings/' + id);
    });
</script>
