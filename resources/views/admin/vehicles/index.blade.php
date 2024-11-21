@extends('layouts.admin')

@section('title', 'Gestión de Vehículos')

@section('content')
<div class="container">
    <h1 class="text-center">Gestión de Vehículos</h1>

    <!-- Botón para crear un nuevo vehículo -->
    <button class="btn btn-info my-3" data-bs-toggle="modal" data-bs-target="#createVehicleModal">Nuevo Vehículo</button>

    <!-- Tabla de vehículos -->
    <table class="table table-bordered">
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
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editVehicleModal" onclick="setEditVehicle({{ $vehicle }})">Editar</button>
                        <form action="{{ route('admin.vehicles.destroy', $vehicle) }}" method="POST" style="display: inline;">
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

@include('admin.vehicles.partials.create')
@include('admin.vehicles.partials.edit')

<script>
    function setEditVehicle(vehicle) {
        document.getElementById('editDescripcion').value = vehicle.descripcion;
        document.getElementById('editEmailConductor').value = vehicle.email_conductor;
        document.getElementById('editPassword').value = ''; // Asegura que el campo de contraseña esté vacío al abrir el modal
        document.getElementById('editVehicleForm').action = '/admin/vehicles/' + vehicle.id_vehiculo;
    }
</script>
@endsection
