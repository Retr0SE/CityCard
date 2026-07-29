<?php

namespace App\Http\Controllers;

use App\Models\City; 
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;

class CityController extends Controller
{
    // 1. READ
    public function index()
    {
        $cities = City::all();
        return view('admin.cities_index', compact('cities'));
    }

    // 2. CREATE
    public function store(StoreCityRequest $request)
    {
        // $request->validated() бере ТІЛЬКИ ті дані, що пройшли перевірку
        City::create($request->validated());

        return back()->with('success', 'Місто успішно додано!');
    }

    // 3. EDIT
    public function edit($id)
    {
        $city = City::findOrFail($id);
        return view('admin.cities_edit', compact('city'));
    }
    
    // 4. UPDATE
    public function update(UpdateCityRequest $request, $id)
    {
        $city = City::findOrFail($id);
        $city->update($request->validated());
        
        return redirect('/admin/cities')->with('success', 'Місто успішно оновлено!');
    }

    // 5. DELETE
    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();

        return back()->with('success', 'Місто видалено з бази!');
    }
}