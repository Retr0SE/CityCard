<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Card;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Models\Vehicle;
use App\Models\TicketPrice;


class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $cards = Card::where('user_id', $user->id)
                     ->with(['transactions' => function($query) {
                         $query->latest(); 
                     }])->get();

        // Завантажуємо весь транспорт, щоб вивести його в список для оплати
        $vehicles = \App\Models\Vehicle::with(['city', 'transportType'])
            ->get()
            ->filter(function ($vehicle) {
                // Залишаємо транспорт лише якщо для цього міста і типу Є тариф
                return \App\Models\TicketPrice::where('city_id', $vehicle->city_id)
                    ->where('transport_type_id', $vehicle->transport_type_id)
                    ->exists();
            });

        return view('user.dashboard', compact('cards', 'vehicles'));
    }

    public function topUp(Request $request)
    {
        // Додаємо перевірку: сума обов'язкова, це число, мінімум 1 грн, максимум 10 000 грн
        $request->validate([
            'card_id' => 'required|exists:cards,id',
            'amount' => 'required|numeric|min:1|max:10000'
        ]);

        $card = Card::findOrFail($request->card_id);

        DB::transaction(function () use ($card, $request) {
            
            $card->balance += $request->amount;
            $card->save();

            Transaction::create([
                'card_id' => $card->id,
                'amount' => $request->amount, // І в історію записуємо саме її
                'transaction_type' => 'TOPUP'
            ]);
        });

        return back()->with('success', 'Рахунок успішно поповнено на ' . number_format($request->amount, 2) . ' грн!');
    }

    // Логіка оплати проїзду
    public function payFare(Request $request)
    {
        $request->validate([
            'card_id' => 'required|exists:cards,id',
            'vehicle_id' => 'required|exists:vehicles,id'
        ]);

        $card = Card::findOrFail($request->card_id);
        $vehicle = Vehicle::with(['city', 'transportType'])->findOrFail($request->vehicle_id);

        // 1. Шукаємо тариф для цього міста та типу транспорту
        $ticketPrice = TicketPrice::where('city_id', $vehicle->city_id)
                                  ->where('transport_type_id', $vehicle->transport_type_id)
                                  ->first();

        if (!$ticketPrice) {
            return back()->withErrors(['error' => 'Тариф для цього транспорту ще не встановлено!']);
        }

        // 2. Перевіряємо баланс
        if ($card->balance < $ticketPrice->price) {
            return back()->withErrors(['error' => 'Недостатньо коштів! Вартість проїзду: ' . $ticketPrice->price . ' грн']);
        }

        // 3. Списуємо кошти та записуємо історію
        DB::transaction(function () use ($card, $ticketPrice, $vehicle) {
            $card->balance -= $ticketPrice->price;
            $card->save();

            Transaction::create([
                'card_id' => $card->id,
                'amount' => $ticketPrice->price,
                'transaction_type' => 'USE',
                'vehicle_id' => $vehicle->id
            ]);
        });

        return back()->with('success', 'Оплачено ' . $ticketPrice->price . ' грн за проїзд (' . $vehicle->transportType->type_name . ' №' . $vehicle->vehicle_number . ')');
    }

    public function redirectToValidator(Request $request)
{
    $request->validate([
        'vehicle_id' => 'required',
        'card_number' => 'required'
    ]);

    $formattedCard = str_pad($request->card_number, 8, '0', STR_PAD_LEFT);

    return redirect('/validator/' . $request->vehicle_id . '?card=' . $formattedCard);
}
}
