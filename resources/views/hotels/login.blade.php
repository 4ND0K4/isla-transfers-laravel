@extends('layouts.app')

@section('title', 'Login Hotel')

@section('content')

<main id="hotel-login" class="vh-100 d-flex justify-content-center align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-lg">
                    <div class="card-header bg-white text-dark text-center border-0">
                        <span class="text-warning fs-3 mb-0">Hotel login</span>
                    </div>
                    <div class="card-body">

                        <form action="{{ route('hotel.login') }}" method="POST">
                            @csrf
                            <!-- Campo de usuario -->
                            <div class="mb-3">
                                <label for="usuario" class="form-label text-success fw-bold">Usuario</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Introduce el usuario" required>
                            </div>
                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label text-success fw-bold">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Introduce el password" required>
                            </div>
                            <!-- Botón -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success text-white" id="login-button">
                                    Acceder
                                    <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button>
                            </div>
                        </form>
                        <!-- Separador -->
                        <div class="d-grid gap-2 w-75">
                            <hr>
                        </div>
                        <!-- Botón (sin acción) para acceder a un formulario de inscripción -->
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-warning text-white" data-bs-toggle="modal" data-bs-target="#">Formulario de Inscripción</button>
                        </div>
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
                         <!-- Mensaje de éxito  -->
                         @if (session('success'))
                         <div id="success-message" class="alert alert-success mt-3 mb-0">
                             <ul class="list-unstyled text-center mb-0">
                                 <li> {{ session('success') }}</li>
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
