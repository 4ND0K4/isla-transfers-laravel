@extends('layouts.admin')

@section('title', 'Gestión de Hoteles')

@section('content')
<div class="d-flex flex-column vh-100">
    <header class="text-secondary text-center p-4 fs-1">
        <h1 class="shadow-sm">Gestión de Hoteles</h1>
    </header>
    <div class="col text-start pb-2 px-4">
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
        <button class="btn btn-outline-secondary fw-bold" data-bs-toggle="modal" data-bs-target="#createHotelModal"><i class="bi bi-plus-circle"></i> Nuevo Hotel</button>
    </div>
    <!-- Tabla -->
    <div class="flex-grow-1 overflow-auto ms-2">
        <div class="table-responsive">
            <table class="table table-light table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>Hotel</th>
                        <th>Zona</th>
                        <th>Comisión</th>
                        <th>Usuario</th>
                        <th>Comisiones</th>
                        <th><i class="bi bi-gear-fill"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hotels as $hotel)
                        <tr>
                            <td>
                                {{
                                    $hotel->id_hotel == 1 ? 'Paraíso Escondido Retreat' :
                                    ($hotel->id_hotel == 2 ? 'Corazón Isleño Inn' :
                                    ($hotel->id_hotel == 3 ? 'Oasis Resort' :
                                    ($hotel->id_hotel == 4 ? 'El Faro Suites' :
                                    ($hotel->id_hotel == 5 ? 'Costa Salvaje Eco Lodge' :
                                    ($hotel->id_hotel == 6 ? 'Arenas Doradas Resort' : 'Hotel desconocido')))))
                                }}
                            </td>
                            <td>
                                {{
                                    $hotel->id_zona  == 1 ? 'Sur' :
                                    ($hotel->id_zona == 2 ? 'Norte' :
                                    ($hotel->id_zona == 3 ? 'Metropolitana' : 'Zona desconocida'))
                                }}
                            </td>
                            <td>{{ $hotel->comision }}</td>
                            <td>{{ $hotel->usuario }}</td>
                            <td>
                                <!-- Botón para ver comisiones -->
                                <button
                                    class="btn btn-sm btn-outline-primary m-1"
                                    title="Ver comisiones"
                                    onclick="loadComisiones({{ $hotel->id_hotel }})"
                                    data-bs-toggle="modal"
                                    data-bs-target="#comisionesModal">
                                    <i class="bi bi-bar-chart-line"></i>
                                </button>
                            </td>
                            <td>
                                <!-- Botón para editar -->
                                <button
                                    class="btn btn-sm btn-outline-warning m-1"
                                    title="Editar hotel"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editHotelModal"
                                    onclick="setEditHotel({{ $hotel }})">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <!-- Botón eliminar -->
                                <form action="{{ route('admin.hotels.destroy', $hotel) }}" method="POST" style="display: inline;" onsubmit="showSpinner(this)">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger m-1"
                                        title="Eliminar hotel">
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

@include('admin.hotels.partials.create')
@include('admin.hotels.partials.edit')
@include('admin.hotels.partials.comisionesModal')

<script>
    function setEditHotel(hotel) {
        document.getElementById('editIdZona').value = hotel.id_zona;
        document.getElementById('editComision').value = hotel.comision;
        document.getElementById('editUsuario').value = hotel.usuario;
        document.getElementById('editHotelForm').action = '/admin/hotels/' + hotel.id_hotel;
    }

    function loadComisiones(hotelId) {
        const tableBody = document.getElementById('comisionesTableBody');
        tableBody.innerHTML = '<tr><td colspan="3" class="text-center">Cargando...</td></tr>'; // Indicador de carga

        fetch(`/admin/hotels/${hotelId}/comisiones`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor.');
                }
                return response.json();
            })
            .then(data => {
                if (data.length > 0) {
                    tableBody.innerHTML = ''; // Limpia el contenido previo
                    data.forEach(comision => {
                        const row = `
                            <tr>
                                <td>${comision.year}</td>
                                <td>${comision.month}</td>
                                <td>${parseFloat(comision.total_comision).toFixed(2)}</td>
                            </tr>
                        `;
                        tableBody.innerHTML += row;
                    });
                } else {
                    tableBody.innerHTML = '<tr><td colspan="3" class="text-center">No hay comisiones disponibles.</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error al cargar las comisiones:', error);
                tableBody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Error al cargar las comisiones.</td></tr>';
            });
    }

    function showSpinner(form) {
        const button = form.querySelector('button[type="submit"]');
        const spinner = button.querySelector('.spinner-border');
        spinner.classList.remove('d-none');
        button.disabled = true;
    }
</script>
@endsection
