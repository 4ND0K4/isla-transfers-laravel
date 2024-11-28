@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mis Reservas</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Destino</th>
                <th>Fecha de Entrada</th>
                <th>Hora de Entrada</th>
                <th>Fecha de Vuelo de Salida</th>
                <th>Hora de Vuelo de Salida</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bookings as $booking)
                <tr>
                    <td>{{ $booking->id_reserva }}</td>
                    <td>{{ $booking->id_destino }}</td>
                    <td>{{ $booking->fecha_entrada }}</td>
                    <td>{{ $booking->hora_entrada }}</td>
                    <td>{{ $booking->fecha_vuelo_salida }}</td>
                    <td>{{ $booking->hora_vuelo_salida }}</td>
                    <td>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#editBookingModal" data-id="{{ $booking->id_reserva }}" data-destino="{{ $booking->id_destino }}" data-fecha-entrada="{{ $booking->fecha_entrada }}" data-hora-entrada="{{ $booking->hora_entrada }}" data-fecha-vuelo-salida="{{ $booking->fecha_vuelo_salida }}" data-hora-vuelo-salida="{{ $booking->hora_vuelo_salida }}">Editar</button>
                        <form action="{{ route('traveler.bookings.delete', $booking->id_reserva) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@include('travelers.partials.edit')
@endsection
