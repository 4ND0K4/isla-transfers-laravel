<div class="container mt-4">
    <!-- Tipo de reserva -->
    <div class="pb-2">
        <select name="id_tipo_reserva" id="{{ isset($booking) ? 'updateIdTypeBookingInput' : 'tipo_reserva' }}" class="form-select form-select-lg" onchange="mostrarCampos('{{ isset($booking) ? 'update' : 'add' }}')">
            <option value="1" {{ isset($booking) && $booking->id_tipo_reserva == 1 ? 'selected' : '' }}>Aeropuerto-Hotel</option>
            <option value="2" {{ isset($booking) && $booking->id_tipo_reserva == 2 ? 'selected' : '' }}>Hotel-Aeropuerto</option>
        </select>
    </div>

    <!-- Campos específicos según tipo de reserva -->
    <div id="aeropuerto-hotel-fields-{{ isset($booking) ? 'update' : 'add' }}" style="display:none;">
        <!-- Fecha Entrada -->
        <div class="form-floating mb-3">
            <input type="date" class="form-control" name="fecha_entrada" value="{{ $booking->fecha_entrada ?? '' }}">
            <label>Fecha de Llegada</label>
        </div>
        <!-- Hora Entrada -->
        <div class="form-floating mb-3">
            <input type="time" class="form-control" name="hora_entrada" value="{{ $booking->hora_entrada ?? '' }}">
            <label>Hora de Llegada</label>
        </div>
    </div>

    <!-- Campos comunes -->
    <div class="form-floating mb-3">
        <input type="email" class="form-control" name="email_cliente" value="{{ $booking->email_cliente ?? '' }}" required>
        <label>Email del Cliente</label>
    </div>
</div>
