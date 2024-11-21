<div class="form-floating mb-3">
    <input type="date" name="fecha_excursion" class="form-control" id="{{ $action }}Fecha" required>
    <label for="{{ $action }}Fecha">Fecha</label>
</div>
<div class="form-floating mb-3">
    <input type="time" name="hora_entrada_excursion" class="form-control" id="{{ $action }}HoraEntrada" required>
    <label for="{{ $action }}HoraEntrada">Hora Entrada</label>
</div>
<div class="form-floating mb-3">
    <input type="time" name="hora_salida_excursion" class="form-control" id="{{ $action }}HoraSalida" required>
    <label for="{{ $action }}HoraSalida">Hora Salida</label>
</div>
<div class="form-floating mb-3">
    <select name="descripcion" class="form-select" id="{{ $action }}Descripcion" required>
        <option value="" disabled selected>Selecciona una excursión</option>
        <option value="Excursión a la Playa">Excursión a la Playa</option>
        <option value="Visita al Volcán">Visita al Volcán</option>
        <option value="Recorrido por la Ciudad">Recorrido por la Ciudad</option>
        <option value="Aventura en la Selva">Aventura en la Selva</option>
        <option value="Tour Cultural">Tour Cultural</option>
        <option value="Paseo en Barco">Paseo en Barco</option>
    </select>
    <label for="{{ $action }}Descripcion">Descripción</label>
</div>
<div class="form-floating mb-3">
    <input type="number" name="num_excursionistas" class="form-control" id="{{ $action }}NumExcursionistas" required>
    <label for="{{ $action }}NumExcursionistas">Número de Excursionistas</label>
</div>
<div class="form-floating mb-3">
    <input type="email" name="email_cliente" class="form-control" id="{{ $action }}EmailCliente" required>
    <label for="{{ $action }}EmailCliente">Email Cliente</label>
</div>
<div class="form-floating mb-3">
    <select name="id_hotel" class="form-select" id="idHotelInput" required>
        <option value="" disabled selected>Selecciona el hotel de recogida</option>
        <option value="1">Paraíso Escondido Retreat</option>
        <option value="2">Corazón Isleño Inn</option>
        <option value="3">Oasis Resort</option>
        <option value="4">El faro Suites</option>
        <option value="5">Costa Salvaje Eco Lodge</option>
        <option value="6">Arenas Doradas Resort</option>
    </select>
    <label for="idHotelInput">Id de destino</label>
</div>
<div class="form-floating mb-3">
    <input type="number" name="id_vehiculo" class="form-control" id="{{ $action }}Vehiculo">
    <label for="{{ $action }}Vehiculo">Vehículo (Opcional)</label>
</div>
