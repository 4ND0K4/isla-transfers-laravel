@extends('layouts.traveler')

@section('content')

    <!-- //////////////////////////////////////////////// BLOQUE PRINCIPAL //////////////////////////////////////////////// -->
    <div class="container">
        <div class="row">
            <div class="col-3 bg-light border rounded-2">
                <!-- Título -->
                <h1 class="text-center pt-3 fw-light text-success fs-4">¡Hola, {{ htmlspecialchars($_SESSION['travelerName'] ?? Auth::user()->nombre) }}!</h1>
                <!-- Subtítulo -->
                <h2 class="text-center text-secondary fw-bold pt-3 fs-6">Gestiona tus reservas.</h2>
                <!-- Botón de crear reserva -->
                <div class="col text-center fw-bold py-3">
                    <button type="button" class="btn btn-lg text-warning" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                        <i class="bi bi-journal-plus display-5"></i>
                    </button>
                </div>
                <!-- Botón de ver reservas -->
                <div class="col text-center fw-bold py-3">
                    <a href="{{ route('traveler.bookings.index') }}" class="btn btn-primary">Ver Mis Reservas</a>
                </div>
                <!-- Reservas en forma de cards -->
                <div id="reservationsContainer" class="container my-4">
                    <div class="row" id="cardsRow">

                    </div>
                </div>
            </div>
            <!-- Calendario -->
            <div class="col-8 bg-white border rounded-2 p-3 m-3" id="calendar"></div>
        </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('traveler.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <button class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Cerrar Sesión
    </button>

<!-- Incluir los modales -->
@include('travelers.partials.create')
@include('travelers.partials.edit')
@include('travelers.partials.profile')

<script>
    function editBooking(id) {
        // Fetch booking details and populate the form
        fetch(`/traveler/bookings/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('editBookingForm').action = `/traveler/bookings/${id}`;
                document.getElementById('editIdReserva').value = data.id_reserva;
                document.getElementById('editIdTipoReserva').value = data.id_tipo_reserva;
                document.getElementById('editLocalizador').value = data.localizador;
                document.getElementById('editEmailCliente').value = data.email_cliente;
                document.getElementById('editNumViajeros').value = data.num_viajeros;
                document.getElementById('editIdDestino').value = data.id_destino;
                document.getElementById('editFechaEntrada').value = data.fecha_entrada;
                document.getElementById('editHoraEntrada').value = data.hora_entrada;
                document.getElementById('editNumeroVueloEntrada').value = data.numero_vuelo_entrada;
                document.getElementById('editOrigenVueloEntrada').value = data.origen_vuelo_entrada;
                document.getElementById('editFechaVueloSalida').value = data.fecha_vuelo_salida;
                document.getElementById('editHoraVueloSalida').value = data.hora_vuelo_salida;

                // Show or hide specific fields based on the type of reservation
                if (data.id_tipo_reserva == 1) {
                    document.getElementById('aeropuerto-hotel-fields-edit').style.display = 'block';
                    document.getElementById('hotel-aeropuerto-fields-edit').style.display = 'none';
                } else if (data.id_tipo_reserva == 2) {
                    document.getElementById('aeropuerto-hotel-fields-edit').style.display = 'none';
                    document.getElementById('hotel-aeropuerto-fields-edit').style.display = 'block';
                }

                $('#editBookingModal').modal('show');
            });
    }

    function deleteBooking(id) {
        if (confirm('¿Está seguro de que desea eliminar esta reserva?')) {
            fetch(`/traveler/bookings/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Reserva eliminada correctamente.');
                    location.reload();
                } else {
                    alert('Error al eliminar la reserva.');
                }
            });
        }
    }
</script>

@endsection
