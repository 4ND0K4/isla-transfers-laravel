<div class="modal fade" id="addBookingModal" tabindex="-1" aria-labelledby="addBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary-subtle">
                <h2 class="modal-title text-center">Añade una nueva reserva</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('traveler.bookings.store') }}" method="POST">
                    @csrf
                    <div class="container mt-4">
                        <!-- Selección del tipo de reserva -->
                        <div class="pb-2">
                            <select name="id_tipo_reserva" id="addIdTipoReserva" class="form-select form-select-lg" aria-label="multiple select" onchange="mostrarCampos('add')">
                                <option value="1">Aeropuerto-Hotel</option>
                                <option value="2">Hotel-Aeropuerto</option>
                                <option value="idayvuelta">Ida/Vuelta</option>
                            </select>
                        </div>

                        <!-- AEROPUERTO -> HOTEL -->
                        <div id="aeropuerto-hotel-fields-add" style="display:none;">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" name="fecha_entrada" id="addFechaEntrada" placeholder="Fecha de entrada">
                                <label for="addFechaEntrada">Día de llegada</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="time" class="form-control" name="hora_entrada" id="addHoraEntrada" placeholder="Hora de entrada">
                                <label for="addHoraEntrada">Hora de llegada</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="numero_vuelo_entrada" id="addNumeroVueloEntrada" placeholder="Número de vuelo de entrada">
                                <label for="addNumeroVueloEntrada">Número de vuelo</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="origen_vuelo_entrada" id="addOrigenVueloEntrada" placeholder="Origen del vuelo de entrada">
                                <label for="addOrigenVueloEntrada">Aeropuerto de origen</label>
                            </div>
                        </div>

                        <!-- HOTEL -> AEROPUERTO -->
                        <div id="hotel-aeropuerto-fields-add" style="display:none;">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" name="fecha_vuelo_salida" id="addFechaVueloSalida" placeholder="Fecha vuelo de salida">
                                <label for="addFechaVueloSalida">Fecha vuelo de salida</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="time" class="form-control" name="hora_vuelo_salida" id="addHoraVueloSalida" placeholder="Hora vuelo de salida">
                                <label for="addHoraVueloSalida">Hora vuelo de salida</label>
                            </div>
                        </div>

                        <!-- Campos comunes -->
                        <div>
                            <div class="form-floating mb-3">
                                <select name="id_destino" class="form-select" id="addIdDestino" required>
                                    <option value="" disabled selected>Selecciona un Id de Destino</option>
                                    <option value="1">Paraíso Escondido Retreat</option>
                                    <option value="2">Corazón Isleño Inn</option>
                                    <option value="3">Recorrido por la Ciudad</option>
                                    <option value="4">Aventura en la Selva</option>
                                    <option value="5">Tour Cultural</option>
                                    <option value="6">Paseo en Barco</option>
                                </select>
                                <label for="addIdDestino">Id de destino</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" name="num_viajeros" id="addNumViajeros" placeholder="Número de viajeros">
                                <label for="addNumViajeros">Número de viajeros</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" name="email_cliente" id="addEmailCliente" placeholder="Email del cliente" required>
                                <label for="addEmailCliente">Email del cliente</label>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="text-center my-3">
                        <button type="submit" id="createBookingButton" class="btn btn-secondary fw-bold text-white">Crear</button>
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
