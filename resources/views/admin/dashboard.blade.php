@extends('layouts.admin')

@section('title', 'Panel de Administración')

@section('content')
    <div class="container">
        <!-- Calendario -->
        <h1 class="text-center py-5">CALENDARIO DE RESERVAS</h1>
        <div id="calendar"></div>

        <!-- Enlaces a las funcionalidades -->
        <div class="d-flex justify-content-center mt-5">
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-primary mx-2">Gestión de Reservas</a>
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-success mx-2">Gestión de Hoteles</a>
            <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-warning mx-2">Gestión de Vehículos</a>
            <a href="{{ route('admin.tours.index') }}" class="btn btn-outline-info mx-2">Gestión de Excursiones</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: {
                    url: '{{ route('admin.bookings.index') }}', // Ruta al controlador
                    method: 'GET',
                    extraParams: {
                        is_calendar: true
                    },
                    failure: function () {
                        alert('Error al cargar los eventos');
                    }
                },
                eventClick: function (info) {
                    Swal.fire({
                        title: 'Detalles de la Reserva',
                        html: `<strong>ID:</strong> ${info.event.id ?? 'N/A'}<br>
                               <strong>Hotel:</strong> ${info.event.extendedProps.id_hotel ?? 'N/A'}<br>
                               <strong>Email Cliente:</strong> ${info.event.extendedProps.email_cliente ?? 'N/A'}`,
                        icon: 'info',
                        confirmButtonText: 'Cerrar'
                    });
                }
            });
            calendar.render();
        });
    </script>
@endsection
