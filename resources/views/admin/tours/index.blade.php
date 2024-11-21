@extends('layouts.admin')

@section('title', 'Gestión de Excursiones')

@section('content')
<div class="container">
    <h1 class="text-center">Gestión de Excursiones</h1>

    <button class="btn btn-dark my-3" data-bs-toggle="modal" data-bs-target="#createTourModal">Nueva Excursión</button>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Hora Entrada</th>
                <th>Hora Salida</th>
                <th>Descripción</th>
                <th># Excursionistas</th>
                <th>Email Cliente</th>
                <th>Hotel</th>
                <th>Vehículo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tours as $tour)
                <tr>
                    <td>{{ $tour->id_excursion }}</td>
                    <td>{{ $tour->fecha_excursion }}</td>
                    <td>{{ $tour->hora_entrada_excursion }}</td>
                    <td>{{ $tour->hora_salida_excursion }}</td>
                    <td>{{ $tour->descripcion }}</td>
                    <td>{{ $tour->num_excursionistas }}</td>
                    <td>{{ $tour->email_cliente }}</td>
                    <td>{{ $tour->id_hotel }}</td>
                    <td>{{ $tour->id_vehiculo }}</td>
                    <td>
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editTourModal" onclick="setEditTour({{ $tour }})">Editar</button>
                        <form action="{{ route('admin.tours.destroy', $tour) }}" method="POST" style="display: inline;">
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

@include('admin.tours.partials.create')
@include('admin.tours.partials.edit')

<script>
    function setEditTour(tour) {
        document.getElementById('editFecha').value = tour.fecha_excursion;
        document.getElementById('editHoraEntrada').value = tour.hora_entrada_excursion;
        document.getElementById('editHoraSalida').value = tour.hora_salida_excursion;
        document.getElementById('editDescripcion').value = tour.descripcion;
        document.getElementById('editNumExcursionistas').value = tour.num_excursionistas;
        document.getElementById('editEmailCliente').value = tour.email_cliente;
        document.getElementById('editHotel').value = tour.id_hotel;
        document.getElementById('editTourForm').action = '/admin/tours/' + tour.id_excursion;
    }
</script>
@endsection
