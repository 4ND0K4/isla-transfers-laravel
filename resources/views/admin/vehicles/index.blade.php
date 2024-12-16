@extends('layouts.admin')

@section('title', 'Gestión de Vehículos')

@section('content')

<div class="d-flex flex-column vh-100">
    <!-- Título -->
    <header class="text-secondary text-center fs-1 p-4">
        <h1 class="shadow-sm">Gestión de  Vehículos</h1>
    </header>
    <!-- Mensajes de error y éxito -->
    @if ($errors->any())
        <div class="alert alert-danger" id="error-messages">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success" id="success-message">
            {{ session('success') }}
        </div>
    @endif
    <!-- Botón para crear un nuevo vehículo -->
    <div class="col text-start py-2 px-4">
        <button class="btn btn-outline-secondary fw-bold" data-bs-toggle="modal" data-bs-target="#createVehicleModal"><i class="bi bi-plus-circle"></i> Nuevo Vehículo</button>
    </div>
    <!-- Tabla de vehículos -->
    <div class="flex-grow-1 overflow-auto ms-2">
        <div class="table-responsive">
            <table class="table table-light table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>ID Vehículo</th>
                        <th>Descripción</th>
                        <th>Email Conductor</th>
                        <th><i class="bi bi-gear-fill"></i></th>
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
                                <form action="{{ route('admin.vehicles.destroy', $vehicle) }}" method="POST" style="display: inline;" onsubmit="showSpinner(this)">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger m-1"
                                        title="Eliminar vehículo">
                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
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

    function showSpinner(form) {
        const button = form.querySelector('button[type="submit"]');
        const spinner = button.querySelector('.spinner-border');
        spinner.classList.remove('d-none');
        button.disabled = true;
    }
</script>
@endsection
