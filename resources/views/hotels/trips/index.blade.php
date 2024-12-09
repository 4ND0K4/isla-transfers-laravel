@extends('layouts.hotel')

@section('title', 'Excursiones del Hotel')

@section('content')
<div class="container my-4">
    <div class="row">
        <h1 class="text-center mb-4">Excursiones</h1>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora de Entrada</th>
                        <th>Hora de Salida</th>
                        <th>Descripción</th>
                        <th>Número de Excursionistas</th>
                        <th>Vehículo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tours as $tour)
                        <tr>
                            <td>{{ $tour->fecha_excursion }}</td>
                            <td>{{ $tour->hora_entrada_excursion }}</td>
                            <td>{{ $tour->hora_salida_excursion }}</td>
                            <td>{{ $tour->descripcion }}</td>
                            <td>{{ $tour->num_excursionistas }}</td>
                            <td>{{ $tour->vehicle->id_vehiculo ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay excursiones disponibles</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
