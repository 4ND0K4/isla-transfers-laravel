@extends('layouts.admin')

@section('title', 'Panel de Administración')

@section('content')
    <div class="container">
        <!-- Calendario -->
        <h1 class="text-center py-5">CALENDARIO DE RESERVAS</h1>
        <div id="calendar"></div>
    </div>
    <div class="container">
        <!-- Enlaces a las funcionalidades -->
        <div class="d-flex justify-content-center mt-5">
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-primary mx-2">Gestión de Reservas</a>
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-success mx-2">Gestión de Hoteles</a>
            <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-warning mx-2">Gestión de Vehículos</a>
            <a href="{{ route('admin.tours.index') }}" class="btn btn-outline-info mx-2">Gestión de Excursiones</a>
        </div>
    </div>
@endsection
