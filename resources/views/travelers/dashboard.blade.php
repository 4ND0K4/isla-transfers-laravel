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

<!-- Incluir los modales -->
@include('travelers.partials.create')
@include('travelers.partials.edit')
@include('travelers.partials.profile')

@endsection
