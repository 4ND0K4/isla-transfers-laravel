@extends('layouts.admin')

@section('title', 'Panel de Administración')

@section('content')

        <div class="row">
            <h1 class="text-center fw-bold text-secondary py-4 fs-1">Panel de Administración</h1>
            <!-- Calendario -->
            <div class="col-12 col-xl-8 bg-white border rounded-2 p-3 m-3" id="calendar"></div>
            <!-- Contenedores -->
            <div class="col-12 col-xl-3 bg-light d-flex flex-column">
                <!-- Container 1 -->
                <div class="bg-white border rounded-2 p-3 m-3 w-100">
                    <p class="text-center">Reservas por Zona</p>
                    <div>{!! $chartZonasPie->container() !!}</div>
                </div>
                <!-- Container 2 -->
                @isset($chartHotelesBar)
                <div class="bg-white border rounded-2 p-3 m-3 w-100">
                    <div>{!! $chartHotelesBar->container() !!}</div>
                </div>
                @endisset
            </div>
        </div>

    <!-- Cargar los scripts necesarios -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {!! $chartZonasPie->script() !!}
    @isset($chartHotelesBar)
    {!! $chartHotelesBar->script() !!}
    @endisset
@endsection
