<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\City;
use App\Models\TransportType;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    // 1. READ: Показуємо весь транспорт разом із містами та типами
    public function index()
    {
        $vehicles = Vehicle::with(['city', 'transportType'])->get();
        
        $cities = City::all();
        $transportTypes = TransportType::all();

        return view('admin.vehicles_index', compact('vehicles', 'cities', 'transportTypes'));
    }

    // 2. CREATE: Збереження нового транспорту
    public function store(Request $request)
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'transport_type_id' => 'required|exists:transport_types,id',
            // Перевірка: номер має бути унікальним тільки в межах обраного міста
            'vehicle_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('vehicles')->where(function ($query) use ($request) {
                    return $query->where('city_id', $request->city_id);
                })
            ]
        ], [
            'vehicle_number.unique' => 'У цьому місті вже існує транспорт з таким номером!'
        ]);

        Vehicle::create($request->all());

        return back()->with('success', 'Транспортний засіб успішно додано!');
    }

    // 3. DELETE: Видалення транспорту
    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();

        return back()->with('success', 'Транспортний засіб видалено!');
    }

    public function edit($id)
    {
        $vehicle = \App\Models\Vehicle::findOrFail($id);
        $cities = \App\Models\City::all();
        $transportTypes = \App\Models\TransportType::all();
        return view('admin.vehicles_edit', compact('vehicle', 'cities', 'transportTypes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'city_id' => 'required', 
            'transport_type_id' => 'required', 
            // При оновленні ігноруємо поточний запис за ID
            'vehicle_number' => [
                'required',
                Rule::unique('vehicles')->where(function ($query) use ($request) {
                    return $query->where('city_id', $request->city_id);
                })->ignore($id)
            ]
        ], [
            'vehicle_number.unique' => 'У цьому місті вже є транспорт з таким номером!'
        ]);
        
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($request->all());
        
        return back()->with('success', 'Дані транспорту успішно оновлено!');
    }
}
