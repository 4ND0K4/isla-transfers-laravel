@extends('layouts.admin')

@section('title', 'Gestión de Vehículos')

@section('content')

<div class="d-flex flex-column vh-100">
    <!-- Título -->
    <header class="text-secondary text-center fs-1 p-4">
        <h1 class="shadow-sm">Gestión de  Vehículos</h1>
    </header>
    <!-- Botón para crear un nuevo vehículo -->
    <div class="col text-start py-2 px-4">
        <button class="btn btn-outline-secondary fw-bold" data-bs-toggle="modal" data-bs-target="#createVehicleModal"><i class="bi bi-plus-circle"></i> Nuevo Vehículo</button>
    </div>
    <!-- Tabla de vehículos -->
    <div class="flex-grow-1 overflow-auto ms-2">
        <div class="table-responsive">
            <table class="table table-light table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID Vehículo</th>
                        <th>Descripción</th>
                        <th>Email Conductor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicles as $vehicle)
                        <tr>
                            <td>{{ $vehicle->id_vehiculo }}</td>
                            <td>{{ $vehicle->descripcion }}</td>
                            <td>{{ $vehicle->email_conductor }}</td>
                            <td>
                                <!-- Botón editar -->
                                <button
                                class="btn btn-sm btn-outline-warning m-1"
                                        title="Editar vehículo"
                                data-bs-toggle="modal"
                                data-bs-target="#editVehicleModal"
                                onclick="setEditVehicle({{ $vehicle }})">
                                <i class="bi bi-pencil-square"></i>
                                </button>
                                <!-- Botón eliminar -->
                                <form action="{{ route('admin.vehicles.destroy', $vehicle) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                    type="submit"
                                    class="btn btn-sm btn-outline-danger m-1"
                                    title="Eliminar excursión">
                                    <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('admin.vehicles.partials.create')

@include('admin.vehicles.partials.edit')

<script>
    function setEditVehicle(vehicle) {
        document.getElementById('editDescripcion').value = vehicle.descripcion;
        document.getElementById('editEmailConductor').value = vehicle.email_conductor;
        document.getElementById('editPassword').value = '';
        document.getElementById('editVehicleForm').action = '/admin/vehicles/' + vehicle.id_vehiculo;
    }
</script>
@endsection
