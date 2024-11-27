@extends('layouts.admin')

@section('title', 'Panel de Administración')

@section('content')
    <div class="container">
        <div class="row">
            <h1 class="text-center fw-bold text-secondary py-4 fs-1">Panel de Administración</h1>
            <!-- Calendario -->
            <div class="col-8 bg-white border rounded-2 p-3 m-3" id="calendar"></div>
            <!-- Contenedores -->
            <div class="col-3 bg-light">
                <!-- Container 1 -->
                <div class="col-3 bg-white border rounded-2 p-3 m-3 w-100">
                    <p class="text-center">Reservas por Hotel (Mes Actual)</p>
                    <div>
                        <div>{!! $chartHotels->container() !!}</div>
                    </div>
                </div>
                <!-- Container 2 -->
                <div class="col-3 bg-white border rounded-2 p-3 m-3 w-100">
                    <p class="text-center">Reservas por Zona</p>
                <div>{!! $chartZonas->container() !!}</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cargar los scripts necesarios -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{ $chartHotels->script() }}
{{ $chartZonas->script() }}
@endsection
