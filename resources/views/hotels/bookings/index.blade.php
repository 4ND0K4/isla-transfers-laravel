
@extends('layouts.hotel')

@section('title', 'Reservas del Hotel')

@section('content')
<div class="container my-4">
    <div class="row">
        <h1 class="text-center mb-4">Reservas del Hotel</h1>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Localizador</th>
                        <th>Tipo de Reserva</th>
                        <th>Email del Cliente</th>
                        <th>Fecha de Reserva</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Número de Viajeros</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr>
                            <td>{{ $booking->localizador }}</td>
                            <td>{{ $booking->id_tipo_reserva == 1 ? 'Aeropuerto-Hotel' : 'Hotel-Aeropuerto' }}</td>
                            <td>{{ $booking->email_cliente }}</td>
                            <td>{{ $booking->fecha_reserva }}</td>
                           <!-- Mostrar fecha dinámica -->
                           <td>
                            {{ $booking->id_tipo_reserva == 1 ? ($booking->fecha_entrada ?? '-') : ($booking->fecha_vuelo_salida ?? '-') }}
                            </td>
                            <!-- Mostrar hora dinámica -->
                            <td>
                                {{ $booking->id_tipo_reserva == 1 ? ($booking->hora_entrada ?? '-') : ($booking->hora_vuelo_salida ?? '-') }}
                            </td>
                            <td>{{ $booking->num_viajeros }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No hay reservas disponibles</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
