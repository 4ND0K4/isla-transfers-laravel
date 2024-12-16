@extends('layouts.hotel')

@section('title', 'Panel de Hotel')

@section('content')

<div class="container my-4">
    <div class="row">
        <!-- Gráfico -->
        <div class="col-xl-4 mb-4">
            <div class="bg-white border rounded-2 p-3 shadow" style="height: 100%; max-height: 100vh;">
                <h2 class="fs-5">Comparación de Comisiones</h2>
                <div style="height: 400px; max-height: 100%; overflow: hidden;">
                    {!! $chart->container() !!}
                </div>
                <div>
                    <!-- Tabla de Comisiones -->
                    <h2 class="mt-5 fs-5"></h2>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Año</th>
                                <th>Mes</th>
                                <th>Comisión Total (€)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($comisiones as $comision)
                                <tr>
                                    <td>{{ $comision->year }}</td>
                                    <td>{{ $comision->month }}</td>
                                    <td>{{ number_format($comision->total_comision, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No hay comisiones disponibles</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-8 mb-4">
            <!-- Tabla de Reservas -->
            <div class="">
                <div class="col-xl-12">
                    <div class="bg-white border rounded-2 p-3 shadow">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="mb-0 fs-5">Últimas reservas realizadas</h2>
                        </div>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Localizador</th>
                                    <th>Tipo de Reserva</th>
                                    <th>Email del Cliente</th>
                                    <th>Fecha de Reserva</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Número de Viajeros</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bookings as $booking)
                                    <tr>
                                        <td>{{ $booking->localizador }}</td>
                                        <td>{{ $booking->id_tipo_reserva == 1 ? 'Aeropuerto-Hotel' : 'Hotel-Aeropuerto' }}</td>
                                        <td>{{ $booking->email_cliente }}</td>
                                        <td>{{ $booking->fecha_reserva }}</td>
                                        <!-- Mostrar fecha dinámica -->
                                        <td>
                                            {{ $booking->id_tipo_reserva == 1 ? ($booking->fecha_entrada ?? '-') : ($booking->fecha_vuelo_salida ?? '-') }}
                                        </td>
                                        <!-- Mostrar hora dinámica -->
                                        <td>
                                            {{ $booking->id_tipo_reserva == 1 ? ($booking->hora_entrada ?? '-') : ($booking->hora_vuelo_salida ?? '-') }}
                                        </td>
                                        <td>{{ $booking->num_viajeros }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No hay reservas disponibles</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Tabla de Excursiones -->


<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{ $chart->script() }}
@endsection

