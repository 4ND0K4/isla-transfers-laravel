<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::all();
        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'email_conductor' => 'required|email|unique:transfer_vehiculo,email_conductor',
            'password' => 'required|string|min:8',
        ]);

        Vehicle::create([
            'descripcion' => $request->descripcion,
            'email_conductor' => $request->email_conductor,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehículo creado correctamente.');
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'email_conductor' => 'required|email|unique:transfer_vehiculo,email_conductor,' . $vehicle->id_vehiculo . ',id_vehiculo',
        ]);

        $data = $request->only(['descripcion', 'email_conductor']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $vehicle->update($data);

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehículo actualizado correctamente.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehículo eliminado correctamente.');
    }
}
