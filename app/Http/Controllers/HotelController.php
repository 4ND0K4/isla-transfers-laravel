<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
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
            $usuario = 'ht' . Str::random(6);
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
