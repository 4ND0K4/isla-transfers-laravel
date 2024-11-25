<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; //para el metodo de usuario unico


class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::all();
        return view('admin.hotels.index', compact('hotels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_zona' => 'required|integer',
            'comision' => 'required|numeric',
            'password' => 'required|string',
        ]);

        // Generar un usuario único
        do {
            $usuario = 'h' . substr(str_shuffle('0123456789'), 0, 8);
        } while (Hotel::where('usuario', $usuario)->exists());

        // Crear el hotel
        Hotel::create([
            'id_zona' => $request->id_zona,
            'comision' => $request->comision,
            'usuario' => $usuario,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel creado correctamente.');
    }

     /**
     * Manejar login de hotel.
     */

     public function showLoginForm()
{
    return view('hotels.login'); // Asegúrate de que esta vista exista
}
    public function login(Request $request)
{
    $request->validate([
        'usuario' => 'required|string',
        'password' => 'required',
    ]);

    $hotel = Hotel::where('usuario', $request->usuario)->first();

    if ($hotel && Hash::check($request->password, $hotel->password)) {
        Auth::guard('hotels')->login($hotel);
        return redirect()->route('hotel.dashboard')->with('success', 'Inicio de sesión exitoso.');
    }

    return back()->withErrors(['usuario' => 'Credenciales incorrectas.']);
}


    public function dashboard()
{
    return view('hotels.dashboard');
}



    public function update(Request $request, Hotel $hotel)
    {
        $request->validate([
            'id_zona' => 'required|integer',
            'comision' => 'required|numeric',
        ]);

        $data = $request->only(['id_zona', 'comision']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $hotel->update($data);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel actualizado correctamente.');
    }

    public function destroy(Hotel $hotel)
    {
        $hotel->delete();

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel eliminado correctamente.');
    }
}
