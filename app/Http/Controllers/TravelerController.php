<?php

namespace App\Http\Controllers;

use App\Models\Traveler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'email' => 'required|email|unique:transfer_viajeros,email', // Campo en minúscula
            'password' => 'required|min:6|confirmed',
        ]);

        Traveler::create([
            'nombre' => $request->nombre,
            'apellido1' => $request->apellido1,
            'apellido2' => $request->apellido2 ?? '', // Valor predeterminado vacío
            'direccion' => $request->direccion ?? '',
            'codigopostal' => $request->codigopostal ?? '',
            'ciudad' => $request->ciudad ?? '',
            'pais' => $request->pais ?? '',
            'email' => $request->email,
            'password' => $request->password, // La contraseña se hashea automáticamente en el modelo
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

        $credentials = $request->only('email', 'password'); // Credenciales en minúscula

        if (Auth::guard('travelers')->attempt($credentials)) {
            return redirect()->route('traveler.dashboard')->with('success', 'Inicio de sesión exitoso.');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas.']);
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
            'email' => "nullable|email|unique:transfer_viajeros,email,$id,id_viajero", // Campos en minúscula
            'password' => 'nullable|min:6|confirmed',
        ]);

        $traveler = Traveler::findOrFail($id);

        // Actualizar datos excepto contraseña si no es proporcionada
        $data = $request->only(['nombre', 'apellido1', 'email']); // Campos en minúscula
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $traveler->update($data);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function dashboard()
    {
        return view('travelers.dashboard'); // Cambia esta ruta según la ubicación de tu vista.
    }

    /**
     * Cerrar sesión del viajero.
     */
    public function logout()
    {
        Auth::guard('travelers')->logout();

        return redirect()->route('traveler.login')->with('success', 'Sesión cerrada correctamente.');
    }
}
