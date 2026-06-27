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
            'card_number' => 'required|string'
        ]);

        $vehicle = Vehicle::findOrFail($vehicle_id);
        // 1. Очищаємо скопійований номер від усіх можливих пробілів (по краях і всередині)
        $cleanCardNumber = str_replace(' ', '', $request->card_number);
        $cleanCardNumber = trim($cleanCardNumber);

        $cleanCardNumber = (int)$cleanCardNumber;
        // 2. Шукаємо очищений номер у базі
        $card = Card::where('card_number', $cleanCardNumber)->first();

        // 1. Перевірка наявності картки
        if (!$card) {
            return back()->with('error', 'Картку не розпізнано!')->with('status', 'error');
        }

        // 2. Шукаємо тариф для цього міста та типу транспорту
        $price = TicketPrice::where('city_id', $vehicle->city_id)
                            ->where('transport_type_id', $vehicle->transport_type_id)
                            ->first();

        if (!$price) {
            return back()->with('error', 'Помилка: тариф не налаштовано!')->with('status', 'error');
        }

        // 3. Перевірка балансу
        if ($card->balance < $price->price) {
            return back()->with('error', 'Недостатньо коштів!')->with('status', 'error');
        }

        // 4. Оплата проїзду (списуємо гроші)
        $card->balance -= $price->price;
        $card->save();

        \App\Models\Transaction::create([
            'card_id' => $card->id,
            'amount' => $price->price,
            'transaction_type' => 'USE', // 'USE' означає використання (оплата проїзду)
            'vehicle_id' => $vehicle->id,
        ]);

        // Успішне повернення на екран
        return back()->with('success', 'Проїзд оплачено: ' . number_format($price->price, 2) . ' ₴')
                     ->with('balance', $card->balance)
                     ->with('status', 'success');
    }
}