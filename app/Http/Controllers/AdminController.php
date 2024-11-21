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
        Auth::guard('admins')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    // Dashboard
    public function dashboard()
    {
        return view('admin.dashboard'); // Vista del panel de administración
    }
}
