<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketPrice;
use App\Models\City;
use App\Models\TransportType;
use App\Models\TicketType;

class TicketPriceController extends Controller
{
    public function index()
    {

        $prices = TicketPrice::with(['city', 'transportType', 'ticketType'])->get();
        $cities = City::all();
        $transportTypes = TransportType::all();
        $ticketTypes = TicketType::all();

        return view('admin.prices_index', compact('prices', 'cities', 'transportTypes', 'ticketTypes'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'transport_type_id' => 'required|exists:transport_types,id',
            'ticket_type_id' => 'required|exists:ticket_types,id', 
            'price' => 'required|numeric|min:0'
        ]);

        $exists = TicketPrice::where('city_id', $request->city_id)
                             ->where('transport_type_id', $request->transport_type_id)
                             ->where('ticket_type_id', $request->ticket_type_id)
                             ->exists();

        if ($exists) {
            return back()->withErrors(['error' => 'Такий тариф (для обраного типу квитка) в цьому місті вже встановлено!']);
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
        $ticketTypes = \App\Models\TicketType::all(); 

        return view('admin.prices_edit', compact('price', 'cities', 'transportTypes', 'ticketTypes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'city_id' => 'required', 
            'transport_type_id' => 'required', 
            'ticket_type_id' => 'required', 
            'price' => 'required|numeric'
        ]);
        
        $ticketPrice = \App\Models\TicketPrice::findOrFail($id);
        $ticketPrice->update($request->all());
        
        return back()->with('success', 'Тариф успішно оновлено!');
    }
}