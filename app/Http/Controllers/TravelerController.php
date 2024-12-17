<?php

namespace App\Http\Controllers;

use App\Models\Traveler;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TravelerController extends Controller
{
    /**
     * Mostrar formulario de registro.
     */
    public function showRegistrationForm()
    {
        return view('travelers.register');
    }

    /**
     * Registrar un nuevo viajero.
     */
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido1' => 'required|string|max:255',
            'email' => 'required|email|unique:transfer_viajeros,email',
            'password' => 'required|min:6|confirmed',
        ]);

        Traveler::create([
            'nombre' => $request->nombre,
            'apellido1' => $request->apellido1,
            'apellido2' => $request->apellido2 ?? '',
            'direccion' => $request->direccion ?? '',
            'codigo_postal' => $request->codigo_postal ?? '',
            'ciudad' => $request->ciudad ?? '',
            'pais' => $request->pais ?? '',
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return redirect()->route('traveler.login')->with('success', 'Registro exitoso. Por favor, inicia sesión.');
    }

    /**
     * Mostrar formulario de login.
     */
    public function showLoginForm()
    {
        return view('travelers.login');
    }

    /**
     * Manejar login de viajero.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email', // Campo en minúscula
            'password' => 'required',
        ]);

        $traveler = Traveler::where('email', $request->email)->first();
        if (!$traveler) {
            return back()->withErrors(['email' => 'El email no es correcto.']);
        }

        $credentials = $request->only('email', 'password'); // Credenciales en minúscula

        if (Auth::guard('travelers')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('traveler.dashboard')->with('success', 'Inicio de sesión exitoso.');
        }

        return back()->withErrors(['password' => 'La contraseña es incorrecta.']);
    }

    /**
     * Mostrar perfil del viajero.
     */
    public function show($id)
    {
        $traveler = Traveler::findOrFail($id);

        return view('travelers.profile', compact('traveler'));
    }

    /**
     * Actualizar perfil del viajero.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'nullable|string|max:255',
            'apellido1' => 'nullable|string|max:255',
            'apellido2' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'codigo_postal' => 'nullable|string|max:20',
            'ciudad' => 'nullable|string|max:255',
            'pais' => 'nullable|string|max:255',
            'email' => "nullable|email|unique:transfer_viajeros,email,$id,id_viajero",
            'password' => 'nullable|min:6|confirmed',
        ]);

        $traveler = Traveler::findOrFail($id);

        $data = $request->only([
            'nombre',
            'apellido1',
            'apellido2',
            'direccion',
            'codigo_postal',
            'ciudad',
            'pais',
            'email'
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $traveler->update($data);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }


    public function dashboard()
    {
        $traveler = Auth::guard('travelers')->user();
        $bookings = Booking::where('email_cliente', $traveler->email)->get();
        return view('travelers.dashboard', compact('bookings', 'traveler'));
    }

    /**
     * Cerrar sesión del viajero.
     */
    public function logout(Request $request)
    {
        Auth::guard('travelers')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('traveler.login')->with('success', 'Sesión cerrada correctamente.');
    }

    public function deleteBooking($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->delete();

            return response()->json(['success' => true, 'message' => 'Reserva eliminada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Hubo un error al intentar eliminar la reserva.']);
        }
    }

}
