<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Mostrar formulario de login
    public function showLoginForm()
    {
        return view('admin.login'); // Vista de login
    }

    // Manejar login
    public function login(Request $request)
    {
        // Validar que el usuario y la contraseña estén presentes
        $credentials = $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        // Intentar autenticación usando 'usuario'
        if (Auth::guard('admins')->attempt(['usuario' => $credentials['usuario'], 'password' => $credentials['password']])) {
            $request->session()->regenerate(); // Regenerar sesión para seguridad
            return redirect()->route('admin.dashboard');
        }

        // Si fallan las credenciales
        return back()->withErrors([
            'usuario' => 'Credenciales incorrectas.',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        // Cierra la sesión del guardia 'admins'
        Auth::guard('admins')->logout();

        // Invalida la sesión actual
        $request->session()->invalidate();

        // Genera un nuevo token CSRF para proteger futuras solicitudes
        $request->session()->regenerateToken();

        // Redirige al login de administradores
        return redirect()->route('admin.login');
    }


    // Dashboard
    public function dashboard()
    {
        return view('admin.dashboard'); // Vista del panel de administración
    }
}
