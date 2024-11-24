<div class="modal fade" id="updateTravelerModal" tabindex="-1" aria-labelledby="updateTravelerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning-subtle">
                <h2 class="modal-title">Modificar Perfil</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                <form action="{{ route('travelers.update', $traveler->id_viajero) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id_traveler" id="updateIdTravelerInput" value="{{ $traveler->id_viajero }}">

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label text-warning" for="updateEmailInput">Email</label>
                        <input class="form-control" type="email" name="email" id="updateEmailInput" value="{{ $traveler->email }}" placeholder="Introduce tu email">
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label text-warning" for="updatePasswordInput">Password</label>
                        <input class="form-control" type="password" name="password" id="updatePasswordInput" placeholder="Introduce una nueva contraseña">
                    </div>

                    <!-- Nombre -->
                    <div class="mb-3">
                        <label class="form-label text-warning" for="updateNameInput">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="updateNameInput" value="{{ $traveler->nombre }}" placeholder="Introduce tu nombre">
                    </div>

                    <!-- Apellido 1 -->
                    <div class="mb-3">
                        <label class="form-label text-warning" for="updateSurname1Input">Apellido1</label>
                        <input class="form-control" type="text" name="apellido1" id="updateSurname1Input" value="{{ $traveler->apellido1 }}" placeholder="Introduce tu primer apellido">
                    </div>

                    <!-- Apellido 2 -->
                    <div class="mb-3">
                        <label class="form-label text-warning" for="updateSurname2Input">Apellido2</label>
                        <input class="form-control" type="text" name="apellido2" id="updateSurname2Input" value="{{ $traveler->apellido2 }}" placeholder="Introduce tu segundo apellido">
                    </div>

                    <!-- Direccion -->
                    <div class="mb-3">
                        <label class="form-label text-warning" for="updateAddressInput">Dirección</label>
                        <input class="form-control" type="text" name="direccion" id="updateAddressInput" value="{{ $traveler->direccion }}" placeholder="Introduce tu dirección aquí">
                    </div>

                    <!-- Código Postal -->
                    <div class="mb-3">
                        <label class="form-label text-warning" for="updateZipCodeInput">Código Postal</label>
                        <input class="form-control" type="text" name="codigoPostal" id="updateZipCodeInput" value="{{ $traveler->codigopostal }}" placeholder="Introduce tu código postal">
                    </div>

                    <!-- Ciudad -->
                    <div class="mb-3">
                        <label class="form-label text-warning" for="updateCityInput">Ciudad</label>
                        <input class="form-control" type="text" name="ciudad" id="updateCityInput" value="{{ $traveler->ciudad }}" placeholder="Introduce tu ciudad">
                    </div>

                    <!-- País -->
                    <div class="mb-3">
                        <label class="form-label text-warning" for="updateCountryInput">País</label>
                        <input class="form-control" type="text" name="pais" id="updateCountryInput" value="{{ $traveler->pais }}" placeholder="Introduce tu país">
                    </div>

                    <!-- Botones de envío -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning fw-bold text-white">Modificar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
