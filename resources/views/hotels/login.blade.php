@extends('layouts.app')

@section('title', 'Login Hotel')

@section('content')

<main id="hotel-login" class="vh-100 d-flex justify-content-center align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-lg">
                    <div class="card-header bg-white text-dark text-center border-0">
                        <div class="">
                            <i class="bi bi-person-fill-gear fs-1"></i>
                        </div>
                        <span class="mb-0">¡Introduce tus credenciales para comenzar el trayecto!</span>
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
                                <button type="submit" class="btn btn-success text-white">Acceder</button>
                            </div>
                            <!-- Mensaje de error al loguearte -->
                            <div class="d-grid gap-2">
                                <?php if (isset($_SESSION['login_error'])) : ?>
                                    <div class="alert alert-danger" role="alert" id="loginError">
                                        <?php echo $_SESSION['login_error']; ?>
                                        <?php unset($_SESSION['login_error']); ?>
                                    </div>
                                <?php endif; ?>
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
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
