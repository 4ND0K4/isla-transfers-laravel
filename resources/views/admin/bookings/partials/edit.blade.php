<div class="modal fade" id="editBookingModal" tabindex="-1" aria-labelledby="editBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Actualice la reserva</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editBookingForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="container mt-4">
                        <input type="hidden" id="editIdReserva" name="id_reserva">
                        <input type="hidden" id="editIdTipoReserva" name="id_tipo_reserva">
                        <input type="hidden" id="editLocalizador" name="localizador">
                        <input type="hidden" id="editEmailCliente" name="email_cliente">

                        <!-- Número de Viajeros -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="num_viajeros" id="editNumViajeros" placeholder="Número de viajeros">
                            <label for="editNumViajeros">Número de viajeros</label>
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

                         <!-- Id Vehículo -->
                         <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="id_vehiculo" id="editIdVehiculo" placeholder="Número de vehiculo">
                            <label for="editIdVehiculo">Vehiculo</label>
                        </div>

                        <!-- Campos específicos para Aeropuerto - Hotel -->
                        <div id="aeropuerto-hotel-fields-edit">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" name="fecha_entrada" id="editFechaEntrada" placeholder="Fecha de entrada">
                                <label for="editFechaEntrada">Fecha Llegada</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="time" class="form-control" name="hora_entrada" id="editHoraEntrada" placeholder="Hora de entrada">
                                <label for="editHoraEntrada">Hora Llegada</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="numero_vuelo_entrada" id="editNumeroVueloEntrada" placeholder="Número de vuelo de entrada">
                                <label for="editNumeroVueloEntrada">Número Vuelo Llegada</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="origen_vuelo_entrada" id="editOrigenVueloEntrada" placeholder="Origen del vuelo de entrada">
                                <label for="editOrigenVueloEntrada">Origen Vuelo</label>
                            </div>
                        </div>

                        <!-- Campos específicos para Hotel - Aeropuerto -->
                        <div id="hotel-aeropuerto-fields-edit">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" name="fecha_vuelo_salida" id="editFechaVueloSalida" placeholder="Fecha del vuelo de salida">
                                <label for="editFechaVueloSalida">Fecha Vuelo Salida</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="time" class="form-control" name="hora_vuelo_salida" id="editHoraVueloSalida" placeholder="Hora del vuelo de salida">
                                <label for="editHoraVueloSalida">Hora Vuelo Salida</label>
                            </div>
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
    </div>
</div>

