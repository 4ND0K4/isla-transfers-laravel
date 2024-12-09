<div class="modal fade" id="editBookingModal" tabindex="-1" role="dialog" aria-labelledby="editBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editBookingModal" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editBookingModalLabel">Editar Reserva</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editIdReserva" name="id_reserva">
                    <input type="hidden" id="editLocalizador" name="localizador">
                    <input type="hidden" id="editEmailCliente" name="email_cliente">
                    <input type="hidden" id="editIdVehiculo" name="id_vehiculo">
                    <input type="hidden" id="editTipoCreadorReserva" name="tipo_creador_reserva">
                    <div class="form-group">
                        <label for="editIdTipoReserva">Tipo de Reserva</label>
                        <select class="form-control" id="editIdTipoReserva" name="id_tipo_reserva" required>
                            <option value="1">Aeropuerto-Hotel</option>
                            <option value="2">Hotel-Aeropuerto</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editNumViajeros">Número de Viajeros</label>
                        <input type="number" class="form-control" id="editNumViajeros" name="num_viajeros" required>
                    </div>
                    <!-- Id Destino -->
                    <div class="form-floating mb-3">
                        <select name="id_destino" class="form-select" id="editIdDestino" required>
                            <option value="" disabled selected>Selecciona un Id de Destino</option>
                            <option value="1">Paraíso Escondido Retreat</option>
                            <option value="2">Corazón Isleño Inn</option>
                            <option value="3">Oasis Resort</option>
                            <option value="4">El faro Suites</option>
                            <option value="5">Costa Salvaje Eco Lodge</option>
                            <option value="6">Arenas Doradas Resort</option>
                        </select>
                        <label for="editIdDestino">Id de destino</label>
                    </div>
                    <div id="aeropuerto-hotel-fields-edit">
                        <div class="form-group">
                            <label for="editFechaEntrada">Fecha de Entrada</label>
                            <input type="date" class="form-control" id="editFechaEntrada" name="fecha_entrada">
                        </div>
                        <div class="form-group">
                            <label for="editHoraEntrada">Hora de Entrada</label>
                            <input type="time" class="form-control" id="editHoraEntrada" name="hora_entrada">
                        </div>
                        <div class="form-group">
                            <label for="editNumeroVueloEntrada">Número de Vuelo de Entrada</label>
                            <input type="text" class="form-control" id="editNumeroVueloEntrada" name="numero_vuelo_entrada">
                        </div>
                        <div class="form-group">
                            <label for="editOrigenVueloEntrada">Origen del Vuelo de Entrada</label>
                            <input type="text" class="form-control" id="editOrigenVueloEntrada" name="origen_vuelo_entrada">
                        </div>
                    </div>
                    <div id="hotel-aeropuerto-fields-edit">
                        <div class="form-group">
                            <label for="editFechaVueloSalida">Fecha de Vuelo de Salida</label>
                            <input type="date" class="form-control" id="editFechaVueloSalida" name="fecha_vuelo_salida">
                        </div>
                        <div class="form-group">
                            <label for="editHoraVueloSalida">Hora de Vuelo de Salida</label>
                            <input type="time" class="form-control" id="editHoraVueloSalida" name="hora_vuelo_salida">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning fw-bold text-white" name="updateTraveler">Modificar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
