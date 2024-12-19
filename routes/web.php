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

// *** RUTAS PARA ADMINISTRADORES ***
Route::prefix('admin')->name('admin.')->group(function () {
    // Login y Logout
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    // Rutas protegidas
    Route::middleware('auth:admins')->group(function () {
        // Dashboard
        Route::get('/dashboard', [BookingController::class, 'dashboard'])->name('dashboard');

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

        // Precios
        Route::get('/prices', [PriceController::class, 'index'])->name('prices.index');
        Route::post('/prices', [PriceController::class, 'store'])->name('prices.store');
        Route::delete('/prices/{price}', [PriceController::class, 'destroy'])->name('prices.destroy');

        // Comisiones Mensuales
        Route::get('/hotels/{hotelId}/comisiones', [HotelController::class, 'comisionesMensuales'])->name('admin.hotels.comisiones');
    });
});
// Ruta para obtener el precio basado en hotel y vehículo
Route::get('/precio/{id_hotel}/{id_vehiculo}', [PriceController::class, 'obtenerPrecio'])->name('precio.obtener');
// Ruta para obtener comisiones por hoteles
Route::get('/admin/hotels/comisiones', [HotelController::class, 'comisionesPorHoteles'])->name('admin.hotels.comisiones');
// Ruta para obtener reservas por zona
Route::get('/reservas/zonas', [BookingController::class, 'reservasPorZona']);


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
        Route::get('/calendar/events', [BookingController::class, 'getCalendarEvents'])->name('calendar.events');
        Route::get('/profile/{id}', [TravelerController::class, 'show'])->name('profile');
        Route::post('/profile/{id}', [TravelerController::class, 'update']);
        Route::get('/traveler/bookings', [BookingController::class, 'index'])->name('traveler.bookings.index');
        Route::get('/traveler/bookings/{id}', [BookingController::class, 'show'])->name('traveler.bookings.show');
        Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
        Route::put('/traveler/bookings/{id}', [BookingController::class, 'update'])->name('traveler.bookings.update');
        Route::delete('/traveler/bookings/{id}', [TravelerController::class, 'deleteBooking'])->name('traveler.bookings.delete');
        //Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    });
});

Route::resource('travelers', TravelerController::class);


// *** RUTAS PARA HOTELES ***
// Rutas públicas de login para hoteles
Route::prefix('hotel')->name('hotel.')->group(function () {
    Route::get('/login', [HotelController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [HotelController::class, 'login']);
    Route::post('/logout', [HotelController::class, 'logout'])->name('logout');

    // Rutas protegidas
    Route::middleware('auth:hotels')->group(function () {
        Route::get('/dashboard', [HotelController::class, 'dashboard'])->name('dashboard');
        Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings', [HotelController::class, 'listAllBookings'])->name('bookings.index');
        Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');
        Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');
        Route::get('/commissions', [HotelController::class, 'listCommissions'])->name('commissions.index');
        Route::get('/trips', [TourController::class, 'index'])->name('trips.index');
        Route::post('/trips', [TourController::class, 'store'])->name('tours.store');
        Route::post('/trips/{tour}', [TourController::class, 'update'])->name('tours.update');
        Route::delete('/trips/{tour}', [TourController::class, 'destroy'])->name('tours.destroy');
    });
});
