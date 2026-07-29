<?php

namespace App\Http\Controllers;

use App\Models\TicketPrice;
use App\Models\City;
use App\Models\TransportType;
use App\Models\TicketType;

use App\Http\Requests\StoreTicketPriceRequest;
use App\Http\Requests\UpdateTicketPriceRequest;

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

    public function store(StoreTicketPriceRequest $request)
    {
        TicketPrice::create($request->validated());

        return back()->with('success', 'Тариф успішно додано!');
    }

    public function edit($id)
    {
        $price = TicketPrice::findOrFail($id);
        $cities = City::all();
        $transportTypes = TransportType::all();
        $ticketTypes = TicketType::all(); 

        return view('admin.prices_edit', compact('price', 'cities', 'transportTypes', 'ticketTypes'));
    }

    public function update(UpdateTicketPriceRequest $request, $id)
    {
        $ticketPrice = TicketPrice::findOrFail($id);
        
        $ticketPrice->update($request->validated());
        
        return back()->with('success', 'Тариф успішно оновлено!');
    }

    public function destroy($id)
    {
        TicketPrice::findOrFail($id)->delete();
        
        return back()->with('success', 'Тариф видалено!');
    }
}