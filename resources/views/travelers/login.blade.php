@extends('layouts.app')

@section('title', 'Login - Isla Transfers')

@section('content')
<main id="traveler">
 <!-- BLOQUE PRINCIPAL -->
 <div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-xl-6">
            <!-- Título -->
            <h1 class="fw-bold display-5 pb-5 w-75">Reserva tu trayecto con Isla Transfers</h1>
            <!-- Subtítulo -->
            <h2 class="fs-5 pb-3">¡Introduce tus credenciales para comenzar el viaje!</h2>
            <form action="{{ route('traveler.login') }}" method="POST">
                @csrf
                <!-- Campo de e-mail-->
                <div class="mb-3">
                    <label for="email" class="form-label text-warning fw-bold">Correo electrónico</label>
                    <input type="email" class="form-control w-75" name="email" id="email"  placeholder="Introduce el email" required>
                </div>
                <!-- Campo de password -->
                <div class="mb-3">
                    <label for="password" class="form-label text-warning fw-bold">Contraseña</label>
                    <input type="password" class="form-control w-75" name="password" id="password" placeholder="Introduce el password" required>
                </div>
                <!-- Botón -->
                <div class="d-grid gap-2 w-75">
                    <button type="submit" class="btn btn-warning">Acceder</button>
                </div>
                <!-- separador -->
                <div class="d-grid gap-2 w-75">
                    <hr>
                </div>
                <!-- Enlace para acceder a registro -->
                <div class="d-grid gap-2 w-75 bg-success">
                    <a href="#" class="btn btn-link text-white text-decoration-none bg-opacity-50">Registrarse</a>
                </div>
                <!-- Mensajes de error -->
                @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Mensajes de éxito -->
                @if (session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif
            </form>
            <!-- Mensaje de éxito si se ha creado un cliente particular
            Aquí
            -->
        </div>
        <!-- Imagen decorativa -->
        <div class="col-xl-6">
            <img src="{{ asset('images/welcome.png') }}" alt="Imagen de taxi con equipaje veraniego en una playa paradisiaca" class="img-fluid mb-4 mb-md-0">
        </div>
    </div>
</div>
</main>
@endsection

