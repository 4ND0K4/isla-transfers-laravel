<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TravelerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\TourController;

// Ruta principal
Route::get('/', function () {
    return view('welcome');
});

// Rutas de viajeros
Route::prefix('traveler')->name('traveler.')->group(function () {
    // Login y Logout
    Route::get('/login', [TravelerController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [TravelerController::class, 'login']);
    Route::post('/logout', [TravelerController::class, 'logout'])->name('logout');

    // Registro
    Route::get('/register', [TravelerController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [TravelerController::class, 'register']);

    // Rutas protegidas por autenticación
    Route::middleware('auth:travelers')->group(function () {
        Route::get('/dashboard', [TravelerController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile/{id}', [TravelerController::class, 'show'])->name('profile');
        Route::post('/profile/{id}', [TravelerController::class, 'update']);
        Route::post('/bookings', [BookingController::class, 'store'])->name('traveler.bookings.store');

    });
});




//Rutas para admins
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    Route::middleware('auth:admins')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/admin/bookings/store', [BookingController::class, 'store'])->name('admin.bookings.store');
    });

});

Route::prefix('traveler')->name('traveler.')->middleware('auth:travelers')->group(function () {
    // Ruta para mostrar todas las reservas del traveler
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');

    // Ruta para crear una nueva reserva (desde el modal)
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

    // Ruta para actualizar una reserva existente
    Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');

    // Ruta para eliminar una reserva
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');
});



Route::prefix('admin')->name('admin.')->middleware('auth:admins')->group(function () {
    // Ruta para mostrar todas las reservas
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');

    // Ruta para crear una nueva reserva
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

    // Ruta para actualizar una reserva existente
    Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');

    // Ruta para eliminar una reserva
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');
});


// Rutas para hoteles
Route::prefix('admin')->name('admin.')->middleware('auth:admins')->group(function () {
    Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
    Route::post('/hotels', [HotelController::class, 'store'])->name('hotels.store');
    Route::put('/hotels/{hotel}', [HotelController::class, 'update'])->name('hotels.update');
    Route::delete('/hotels/{hotel}', [HotelController::class, 'destroy'])->name('hotels.destroy');
});



Route::prefix('admin')->name('admin.')->middleware('auth:admins')->group(function () {
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
    Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');
    Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');
});


Route::prefix('admin')->name('admin.')->middleware('auth:admins')->group(function () {
    Route::get('/tours', [TourController::class, 'index'])->name('tours.index');
    Route::post('/tours', [TourController::class, 'store'])->name('tours.store');
    Route::put('/tours/{tour}', [TourController::class, 'update'])->name('tours.update');
    Route::delete('/tours/{tour}', [TourController::class, 'destroy'])->name('tours.destroy');
});

//Rutas para usuarios hoteles
Route::middleware(['auth:hotels'])->group(function () {
    Route::post('/hotel/bookings/store', [BookingController::class, 'store'])->name('hotel.bookings.store');
    Route::get('/dashboard', [HotelController::class, 'dashboard'])->name('hotel.dashboard')->middleware('auth:hotels');

});
