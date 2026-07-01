<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Card;
use App\Models\TicketPrice;

class ValidatorController extends Controller
{
    // Відображення екрану термінала
    public function showTerminal($vehicle_id)
    {
        $vehicle = Vehicle::with(['city', 'transportType'])->findOrFail($vehicle_id);
        
        return view('validator.terminal', compact('vehicle'));
    }

    
    public function processPayment(Request $request, $vehicle_id)
    {
        $request->validate([
            'card_number' => 'required|exists:cards,card_number',
        ]);

        $card = \App\Models\Card::where('card_number', $request->card_number)->firstOrFail();
        $vehicle = \App\Models\Vehicle::with(['city', 'transportType'])->findOrFail($vehicle_id);

        // 1. ШУКАЄМО ТАРИФ: Місто + Транспорт + ТИП КВИТКА (Пільговий, Студентський тощо)
        $ticketPrice = \App\Models\TicketPrice::where('city_id', $vehicle->city_id)
                                  ->where('transport_type_id', $vehicle->transport_type_id)
                                  ->where('ticket_type_id', $card->ticket_type_id) // <-- ОСЬ ВАЖЛИВИЙ РЯДОК
                                  ->first();

        if (!$ticketPrice) {
            return back()->with('error', 'Для вашого типу картки тариф ще не встановлено!')->with('status', 'error');
        }

        // 2. Перевіряємо баланс
        if ($card->balance < $ticketPrice->price) {
            return back()->with('error', 'Недостатньо коштів! Вартість: ' . $ticketPrice->price . ' ₴')->with('status', 'error');
        }

        // 3. Списуємо кошти та записуємо історію
        \Illuminate\Support\Facades\DB::transaction(function () use ($card, $ticketPrice, $vehicle) {
            $card->balance -= $ticketPrice->price;
            $card->save();

            \App\Models\Transaction::create([
                'card_id' => $card->id,
                'amount' => $ticketPrice->price,
                'transaction_type' => 'USE',
                'vehicle_id' => $vehicle->id
            ]);
        });

        // 4. Повертаємо успішний статус на екран валідатора
        return back()
            ->with('status', 'success')
            ->with('success', 'Оплачено ' . $ticketPrice->price . ' ₴')
            ->with('balance', $card->balance);
    }
}