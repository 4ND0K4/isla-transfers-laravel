
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Reserva</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('traveler.bookings.update', $booking->id_reserva) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="id_destino">Destino</label>
            <input type="text" class="form-control" id="id_destino" name="id_destino" value="{{ $booking->id_destino }}" required>
        </div>
        <div class="form-group">
            <label for="fecha_entrada">Fecha de Entrada</label>
            <input type="date" class="form-control" id="fecha_entrada" name="fecha_entrada" value="{{ $booking->fecha_entrada }}">
        </div>
        <div class="form-group">
            <label for="hora_entrada">Hora de Entrada</label>
            <input type="time" class="form-control" id="hora_entrada" name="hora_entrada" value="{{ $booking->hora_entrada }}">
        </div>
        <div class="form-group">
            <label for="fecha_vuelo_salida">Fecha de Vuelo de Salida</label>
            <input type="date" class="form-control" id="fecha_vuelo_salida" name="fecha_vuelo_salida" value="{{ $booking->fecha_vuelo_salida }}">
        </div>
        <div class="form-group">
            <label for="hora_vuelo_salida">Hora de Vuelo de Salida</label>
            <input type="time" class="form-control" id="hora_vuelo_salida" name="hora_vuelo_salida" value="{{ $booking->hora_vuelo_salida }}">
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>
@endsection
