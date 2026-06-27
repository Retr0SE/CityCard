<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketPrice;
use App\Models\City;
use App\Models\TransportType;

class TicketPriceController extends Controller
{
    public function index()
    {
        $prices = TicketPrice::with(['city', 'transportType'])->get();
        $cities = City::all();
        $transportTypes = TransportType::all();

        return view('admin.prices_index', compact('prices', 'cities', 'transportTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'transport_type_id' => 'required|exists:transport_types,id',
            'price' => 'required|numeric|min:0'
        ]);

        // Перевіряємо, чи для цієї комбінації міста і транспорту вже існує тариф
        $exists = TicketPrice::where('city_id', $request->city_id)
                             ->where('transport_type_id', $request->transport_type_id)
                             ->exists();

        if ($exists) {
            return back()->withErrors(['error' => 'Тариф для цього транспорту в цьому місті вже встановлено!']);
        }

        TicketPrice::create($request->all());

        return back()->with('success', 'Тариф успішно додано!');
    }

    public function destroy($id)
    {
        TicketPrice::findOrFail($id)->delete();
        return back()->with('success', 'Тариф видалено!');
    }

    public function edit($id)
    {
        $price = \App\Models\TicketPrice::findOrFail($id);
        $cities = \App\Models\City::all();
        $transportTypes = \App\Models\TransportType::all();
        return view('admin.prices_edit', compact('price', 'cities', 'transportTypes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'city_id' => 'required', 
            'transport_type_id' => 'required', 
            'price' => 'required|numeric'
        ]);
        
        $ticketPrice = \App\Models\TicketPrice::findOrFail($id);
        $ticketPrice->update($request->all());
        
        return back()->with('success', 'Тариф успішно оновлено!');
    }
}
