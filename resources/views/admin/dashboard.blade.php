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
                <div class="col-3 bg-white border rounded-2 p-3 m-3 w-100 h-50">Contenedor 1</div>
                <!-- Container 2 -->
                <div class="col-3 bg-white border rounded-2 p-3 m-3 w-100 h-50">Contenedor 2</div>
            </div>
        </div>
    </div>
@endsection
