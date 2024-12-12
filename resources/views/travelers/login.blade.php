@extends('layouts.app')

@section('title', 'Login - Isla Transfers')

@section('content')

<main id="traveler-login" class="vh-100 d-flex justify-content-center align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-lg">
                    <div class="card-header bg-white text-dark text-center border-0">
                        <div class="">
                            <i class="fa-sharp-duotone fa-solid fa-user"></i>
                        </div>
                        <span class="mb-0">¡Introduce tus credenciales para comenzar el trayecto!</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('traveler.login') }}" method="POST">
                            @csrf
                            <!-- Campo de e-mail-->
                            <div class="mb-3">
                                <label for="email" class="form-label text-warning fw-bold">Correo electrónico</label>
                                <input type="email" class="form-control" name="email" id="email"  placeholder="Introduce el email" required>
                            </div>
                            <!-- Campo de password -->
                            <div class="mb-3">
                                <label for="password" class="form-label text-warning fw-bold">Contraseña</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Introduce el password" required>
                            </div>
                            <!-- Botón -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning">Acceder</button>
                            </div>
                            <!-- separador -->
                            <div class="d-grid gap-2">
                                <hr>
                            </div>
                            <!-- Enlace para acceder a registro -->
                            <div class="d-grid gap-2 bg-success">
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
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

