@extends('layouts.admin')

@section('title', 'Gestión de Reservas')

@section('content')
<div class="d-flex flex-column vh-100">
    <!-- Título -->
    <header class="text-secondary text-center p-4 fs-1">
        <h1 class="shadow-sm">Gestión de Reservas</h1>
    </header>

   <!-- Botón creación de reservas -->
    <div class="row align-items-center">
        <div class="col text-start pb-2 px-4">
            <button type="button" class="btn btn-outline-secondary fw-bold" data-bs-toggle="modal" data-bs-target="#addBookingModal"><i class="bi bi-plus-circle"></i> Nueva reserva</button>
        </div>

        <!-- Filtros -->
        <div class="col text-end pb-2 px-4">
            <form method="GET" action="{{ route('admin.bookings.index') }}" class="d-flex justify-content-end">
                <div class="me-2">
                    <label for="id_tipo_reserva" class="form-label">Reservas: </label>
                    <select name="id_tipo_reserva" id="id_tipo_reserva" class="form-select d-inline w-auto">
                        <option value="">Todos</option>
                        <option value="1" {{ request('id_tipo_reserva') == 1 ? 'selected' : '' }}>Aeropuerto - Hotel</option>
                        <option value="2" {{ request('id_tipo_reserva') == 2 ? 'selected' : '' }}>Hotel - Aeropuerto</option>
                    </select>
                </div>
                <div class="me-2">
                    <label for="year" class="form-label">Año:</label>
                    <select name="year" id="year" class="form-select d-inline w-auto">
                        <option value="">Todos</option>
                        <option value="2024" {{ request('year') == 2024 ? 'selected' : '' }}>2024</option>
                        <option value="2025" {{ request('year') == 2025 ? 'selected' : '' }}>2025</option>
                    </select>
                </div>
                <div class="me-2">
                    <label for="month" class="form-label">Mes:</label>
                    <select name="month" id="month" class="form-select d-inline w-auto">
                        <option value="">Todos</option>
                        <option value="1" {{ request('month') == 1 ? 'selected' : '' }}>Enero</option>
                        <option value="2" {{ request('month') == 2 ? 'selected' : '' }}>Febrero</option>
                        <option value="3" {{ request('month') == 3 ? 'selected' : '' }}>Marzo</option>
                        <option value="4" {{ request('month') == 4 ? 'selected' : '' }}>Abril</option>
                        <option value="5" {{ request('month') == 5 ? 'selected' : '' }}>Mayo</option>
                        <option value="6" {{ request('month') == 6 ? 'selected' : '' }}>Junio</option>
                        <option value="7" {{ request('month') == 7 ? 'selected' : '' }}>Julio</option>
                        <option value="8" {{ request('month') == 8 ? 'selected' : '' }}>Agosto</option>
                        <option value="9" {{ request('month') == 9 ? 'selected' : '' }}>Septiembre</option>
                        <option value="10" {{ request('month') == 10 ? 'selected' : '' }}>Octubre</option>
                        <option value="11" {{ request('month') == 11 ? 'selected' : '' }}>Noviembre</option>
                        <option value="12" {{ request('month') == 12 ? 'selected' : '' }}>Diciembre</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-outline-secondary">Filtrar</button>
            </form>
        </div>
    </div>

    <!-- Tabla -->
    <div class="flex-grow-1 overflow-auto ms-2">
        <div class="table-responsive">
            <table class="table table-light table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Localizador</th>
                        <th scope="col">Recogida</th>
                        <th scope="col">Hotel</th>
                        <th scope="col">Email Cliente</th>
                        <th scope="col">Pasajeros</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Hora</th>
                        <th scope="col">Número</th>
                        <th scope="col">Origen</th>
                        <th scope="col">Vehículo</th>
                        <th scope="col"><!--Botones--></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id_reserva }}</td>
                            <td>{{ $booking->localizador }}</td>
                            <td>{{ $booking->id_tipo_reserva == 1 ? 'Aeropuerto' : 'Hotel' }}</td>
                            <td>
                                {{
                                    $booking->id_hotel == 1 ? 'Paraíso Escondido Retreat' :
                                    ($booking->id_hotel == 2 ? 'Corazón Isleño Inn' :
                                    ($booking->id_hotel == 3 ? 'Oasis Resort' :
                                    ($booking->id_hotel == 4 ? 'El Faro Suites' :
                                    ($booking->id_hotel == 5 ? 'Costa Salvaje Eco Lodge' :
                                    ($booking->id_hotel == 6 ? 'Arenas Doradas Resort' : 'Hotel desconocido')))))
                                }}
                            </td>
                            <td>{{ $booking->email_cliente }}</td>
                            <td>{{ $booking->num_viajeros }}</td>
                            <!-- Mostrar fecha dinámica -->
                            <td>
                                {{ $booking->id_tipo_reserva == 1 ? ($booking->fecha_entrada ?? '-') : ($booking->fecha_vuelo_salida ?? '-') }}
                            </td>
                            <!-- Mostrar hora dinámica -->
                            <td>
                                {{ $booking->id_tipo_reserva == 1 ? ($booking->hora_entrada ?? '-') : ($booking->hora_vuelo_salida ?? '-') }}
                            </td>
                            <td>{{ $booking->numero_vuelo_entrada ?? '-' }}</td>
                            <td>{{ $booking->origen_vuelo_entrada ?? '-' }}</td>
                            <td>{{ $booking->id_vehiculo ?? '-' }}</td>
                            <td>
                                <button
                                    class="btn btn-sm btn-outline-warning m-1"
                                    title="Editar reserva"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editBookingModal"
                                    data-booking='@json($booking)'
                                    onclick="setEditBooking(this)">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <form action="{{ route('admin.bookings.destroy', $booking->id_reserva) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                    class="btn btn-sm btn-outline-danger m-1"
                                    title="Eliminar reserva">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center">No se encontraron reservas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
