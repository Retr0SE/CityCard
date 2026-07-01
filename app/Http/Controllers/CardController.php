<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Card;

class CardController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'ticket_type_id' => 'required|exists:ticket_types,id',
        ]);

        $userCardCount = Card::where('user_id', auth()->id())->count();
        
        if ($userCardCount >= 5) {
            return back()->withErrors(['error' => 'Ви досягли ліміту! У вас вже є 5 карток.']);
        }

        $cardNumber = $this->generateUniqueCardNumber();

        // 3. Створюємо картку
        $card = Card::create([
            'user_id' => auth()->id(),
            'card_number' => $cardNumber,
            'ticket_type_id' => $request->ticket_type_id,
            'balance' => 0,
        ]);

        return redirect('/dashboard?active_card=' . $card->id)->with('success', 'Нову картку успішно додано!');
    }


    private function generateUniqueCardNumber()
    {
        do {
            $number = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Перевіряємо, чи існує вже такий номер у базі
            $exists = Card::where('card_number', $number)->exists();
            
        } while ($exists); // Якщо такий номер вже є, генеруємо заново

        return $number;
    }

    public function destroy($id)
    {
        // Шукаємо картку, переконуючись, що вона належить саме поточному користувачу
        $card = Card::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        
        // Видаляємо картку з бази даних
        $card->delete();

        return back()->with('success', 'Картку успішно видалено!');
    }
}