<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TravelerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\PriceController;

// Ruta principal
Route::get('/', function () {
    return view('welcome');
});

// *** RUTAS PARA VIAJEROS ***
Route::prefix('traveler')->name('traveler.')->group(function () {
    // Login y Registro
    Route::get('/login', [TravelerController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [TravelerController::class, 'login']);
    Route::post('/logout', [TravelerController::class, 'logout'])->name('logout');
    Route::get('/register', [TravelerController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [TravelerController::class, 'register']);

    // Rutas protegidas
    Route::middleware('auth:travelers')->group(function () {
        Route::get('/dashboard', [TravelerController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile/{id}', [TravelerController::class, 'show'])->name('profile');
        Route::post('/profile/{id}', [TravelerController::class, 'update']);

        // Reservas
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');
        Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');

        // Calendario
        Route::get('/calendar/events', [BookingController::class, 'getCalendarEvents'])->name('calendar.events');
    });
});
Route::resource('travelers', TravelerController::class);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// *** RUTAS PARA ADMINISTRADORES ***
Route::prefix('admin')->name('admin.')->group(function () {
    // Login y Logout
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    // Rutas protegidas
    Route::middleware('auth:admins')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Reservas
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');
        Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');

        // Calendario
        Route::get('/calendar/events', [BookingController::class, 'getCalendarEvents'])->name('calendar.events');

        // Hoteles
        Route::resource('hotels', HotelController::class)->except(['show', 'create', 'edit']);

        // Vehículos
        Route::resource('vehicles', VehicleController::class)->except(['show', 'create', 'edit']);

        // Tours
        Route::resource('tours', TourController::class)->except(['show', 'create', 'edit']);

    });
});

// *** RUTAS PARA HOTELES ***
// Rutas públicas de login para hoteles
Route::get('/hotel/login', [HotelController::class, 'showLoginForm'])->name('hotel.login'); // Para mostrar el formulario de login
Route::post('/hotel/login', [HotelController::class, 'login'])->name('hotel.login.post'); // Para procesar el login

// Rutas protegidas para hoteles (requieren autenticación)
Route::prefix('hotel')->name('hotel.')->middleware('auth:hotels')->group(function () {
    Route::get('/dashboard', [HotelController::class, 'dashboard'])->name('dashboard');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
});


Route::middleware(['auth:hotels'])->group(function () {
    Route::get('/hotel/bookings', [BookingController::class, 'index'])->name('hotel.bookings.index');
    Route::post('/hotel/bookings', [BookingController::class, 'store'])->name('hotel.bookings.store');
});


// Ruta para el dashboard del hotel
Route::middleware(['auth:hotels'])->group(function () {
    Route::get('/hotel/dashboard', [HotelController::class, 'dashboard'])->name('hotel.dashboard');
});

// Ruta para obtener el precio basado en hotel y vehículo


Route::get('/precio/{id_hotel}/{id_vehiculo}', [PriceController::class, 'obtenerPrecio'])->name('precio.obtener');

//funcionan
Route::get('/admin/hotels/comisiones', [HotelController::class, 'comisionesPorHoteles'])->name('admin.hotels.comisiones');
Route::get('/admin/hotels/{id}/comisiones', [HotelController::class, 'comisionesMensuales'])->name('admin.hotels.comisiones');

Route::prefix('admin')->name('admin.')->middleware(['auth:admins'])->group(function () {
    Route::get('/prices', [PriceController::class, 'index'])->name('prices.index');
    Route::post('/prices', [PriceController::class, 'store'])->name('prices.store');
    Route::delete('/prices/{price}', [PriceController::class, 'destroy'])->name('prices.destroy');
});


//Vista de graficos
Route::get('/hotels/{hotelId}/comparar-meses', [HotelController::class, 'compararMesActualAnterior'])
    ->name('hotels.compararMeses');

