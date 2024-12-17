@extends('layouts.app')

@section('title', 'Login - Isla Transfers')

@section('content')

<main id="traveler-login" class="vh-100 d-flex justify-content-center align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-lg">
                    <div class="card-header bg-white text-dark text-center border-0">
                        <span class="fs-3 text-success mb-0">Cliente login</span>
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
                                <button type="submit" class="btn btn-warning" id="login-button">
                                    Acceder
                                    <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button>
                            </div>
                            <!-- separador -->
                            <div class="d-grid gap-2">
                                <hr>
                            </div>
                            <!-- Enlace para acceder a registro -->
                            <div class="d-grid gap-2 bg-success">
                                <a href="{{ route('traveler.register') }}" class="btn btn-link text-white text-decoration-none bg-opacity-50">Registrarse</a>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer border-0">
                        <!-- Mensajes de error -->
                        @if ($errors->any())
                            <div id="error-messages" class="alert alert-danger mt-3 mb-0">
                                <ul class="list-unstyled text-center mb-0">
                                    @foreach ($errors->all() as $error)
                                    <li><i class="fa-regular fa-id-card"></i> {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <!-- Mensaje de éxito si se ha creado un cliente particular -->
                        @if (session('success'))
                            <div id="success-message" class="alert alert-success mt-3 mb-0">
                                <ul class="list-unstyled text-center mb-0">
                                    <li><i class="fa-solid fa-passport"></i> {{ session('success') }}</li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.getElementById('login-button').addEventListener('click', function() {
        document.getElementById('spinner').classList.remove('d-none');
    });
</script>
@endsection

