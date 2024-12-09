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
        Route::get('/bookings', [TravelerController::class, 'index'])->name('bookings.index');
        Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
        Route::put('/bookings/{id}', [TravelerController::class, 'updateBooking'])->name('bookings.update');
        Route::delete('/bookings/{id}', [TravelerController::class, 'deleteBooking'])->name('bookings.destroy');

        // Calendario
        Route::get('/calendar/events', [TravelerController::class, 'getCalendarEvents'])->name('calendar.events');
    });
});
Route::resource('travelers', TravelerController::class);

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

        // Dashboard
        Route::get('/dashboard', [BookingController::class, 'dashboard'])->name('dashboard');
    });
});

// *** RUTAS PARA HOTELES ***
// Rutas públicas de login para hoteles
Route::get('/hotel/login', [HotelController::class, 'showLoginForm'])->name('hotel.login'); // Para mostrar el formulario de login
Route::post('/hotel/login', [HotelController::class, 'login'])->name('hotel.login.post'); // Para procesar el login

Route::prefix('hotel')->middleware('auth:hotels')->group(function () {
    Route::get('/dashboard', [HotelController::class, 'dashboard'])->name('hotel.dashboard');
    Route::post('/bookings/store', [BookingController::class, 'store'])->name('hotel.bookings.store'); // Add this line
    Route::get('/bookings', [HotelController::class, 'listAllBookings'])->name('hotel.bookings.index');
    Route::get('/commissions', [HotelController::class, 'listCommissions'])->name('hotel.commissions.index');
    Route::get('/trips', [TourController::class, 'index'])->name('hotel.trips.index');
    Route::post('/logout', [HotelController::class, 'logout'])->name('hotel.logout');
    Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('hotel.bookings.update');
});

// Ruta para el dashboard del hotel
Route::middleware(['auth:hotels'])->group(function () {
    Route::get('/hotel/dashboard', [HotelController::class, 'dashboard'])->name('hotel.dashboard');
});

// Ruta para obtener el precio basado en hotel y vehículo

Route::get('/precio/{id_hotel}/{id_vehiculo}', [PriceController::class, 'obtenerPrecio'])->name('precio.obtener');

//funcionan
Route::get('/admin/hotels/comisiones', [HotelController::class, 'comisionesPorHoteles'])->name('admin.hotels.comisiones');
Route::get('/admin/hotels/{hotelId}/comisiones', [HotelController::class, 'comisionesMensuales'])->name('admin.hotels.comisiones');
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/hotels/{hotelId}/comisiones', [HotelController::class, 'comisionesMensuales'])->name('admin.hotels.comisiones');
    // Otras rutas exclusivas de admin...
});
Route::middleware(['auth:admins'])->group(function () {
    Route::get('/admin/hotels/{hotelId}/comisiones', [HotelController::class, 'comisionesMensuales'])->name('admin.hotels.comisiones');
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');
});
Route::get('/admin/dashboard', [BookingController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/reservas/zonas', [BookingController::class, 'reservasPorZona']);

Route::prefix('admin')->name('admin.')->middleware(['auth:admins'])->group(function () {
    Route::get('/prices', [PriceController::class, 'index'])->name('prices.index');
    Route::post('/prices', [PriceController::class, 'store'])->name('prices.store');
    Route::delete('/prices/{price}', [PriceController::class, 'destroy'])->name('prices.destroy');
});

Route::middleware(['auth:travelers'])->group(function () {
    Route::get('/traveler/bookings', [TravelerController::class, 'index'])->name('traveler.bookings.index');
    Route::put('/traveler/bookings/{id}', [BookingController::class, 'update'])->name('traveler.bookings.update');
    Route::delete('/traveler/bookings/{id}', [TravelerController::class, 'deleteBooking'])->name('traveler.bookings.delete');
    Route::get('/traveler/calendar-events', [TravelerController::class, 'getCalendarEvents'])->name('traveler.calendar.events');
});





