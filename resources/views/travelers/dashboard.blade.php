@extends('layouts.traveler')

@section('content')
<div class="container">
    <h1>Bienvenido al Dashboard de Viajeros</h1>

    <div class="col text-start pt-4 pb-2">
            <button type="button" class="btn btn-outline-dark fw-bold" data-bs-toggle="modal" data-bs-target="#addBookingModal">Nueva reserva</button>
        </div>
    <div class="container">
        <!-- Calendario -->
        <h1 class="text-center py-5">CALENDARIO DE RESERVAS</h1>
        <div id="calendar"></div>
    </div>
</div>

<!-- Incluir el modal -->
@include('travelers.partials.create')


@endsection
