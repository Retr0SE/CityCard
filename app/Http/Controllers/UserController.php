<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Card;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Models\Vehicle;
use App\Models\TicketPrice;
use App\Models\TicketType;

class UserController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $cards = Card::where('user_id', $user->id)
                    ->orderBy('id', 'asc') 
                    ->with(['ticketType', 'transactions' => function($query) {
                        $query->latest(); 
                    }])->get();

        $vehicles = Vehicle::with(['city', 'transportType'])
            ->get()
            ->filter(function ($vehicle) {
                return TicketPrice::where('city_id', $vehicle->city_id)
                    ->where('transport_type_id', $vehicle->transport_type_id)
                    ->exists();
            });

        $ticketTypes = TicketType::all();

        // Беремо ID картки з URL або беремо першу з бази, якщо вона є
        $activeCardId = $request->query('active_card', $cards->isNotEmpty() ? $cards->first()->id : null);

        return view('user.dashboard', compact('cards', 'vehicles', 'ticketTypes', 'activeCardId'));
    }

    public function topUp(Request $request)
    {
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
                'amount' => $request->amount,
                'transaction_type' => 'TOPUP'
            ]);
        });

        // ПОВЕРТАЄМО НА ТУ САМУ КАРТКУ
        return redirect('/dashboard?active_card=' . $card->id)
            ->with('success', 'Рахунок успішно поповнено на ' . number_format($request->amount, 2) . ' грн!');
    }

    public function payFare(Request $request, $vehicle_id = null)
    {
        if ($vehicle_id) {
            $request->merge(['vehicle_id' => $vehicle_id]);
        }

        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id'
        ]);

        if ($request->filled('card_id')) {
            $card = Card::findOrFail($request->card_id);
        } elseif ($request->filled('card_number')) {
            $card = Card::where('card_number', $request->card_number)->first();
            if (!$card) {
                return back()->with('error', 'Картку не знайдено!')->with('status', 'error');
            }
        } else {
            return back()->withErrors(['error' => 'Дані картки не передано!']);
        }

        $vehicle = Vehicle::with(['city', 'transportType'])->findOrFail($request->vehicle_id);

        $ticketPrice = TicketPrice::where('city_id', $vehicle->city_id)
                                  ->where('transport_type_id', $vehicle->transport_type_id)
                                  ->where('ticket_type_id', $card->ticket_type_id)
                                  ->first();

        if (!$ticketPrice) {
            return back()
                ->withErrors(['error' => 'Для вашого типу картки тариф на цей маршрут ще не встановлено!'])
                ->with('error', 'Тариф не встановлено!')
                ->with('status', 'error');
        }

        if ($card->balance < $ticketPrice->price) {
            return back()
                ->withErrors(['error' => 'Недостатньо коштів! Вартість проїзду: ' . $ticketPrice->price . ' ₴'])
                ->with('error', 'Недостатньо коштів! Ціна: ' . $ticketPrice->price . ' ₴')
                ->with('status', 'error');
        }

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

        if ($request->filled('card_id')) {
            return redirect('/dashboard?active_card=' . $card->id)
                ->with('success', 'Оплачено ' . $ticketPrice->price . ' ₴ за проїзд (' . $vehicle->transportType->type_name . ' №' . $vehicle->vehicle_number . ')');
        }

        return back()
            ->with('status', 'success')
            ->with('success', 'Оплачено ' . $ticketPrice->price . ' ₴')
            ->with('balance', $card->balance);
    }

    public function redirectToValidator(Request $request)
    {
        $request->validate([
            'card_id' => 'required|exists:cards,id',
            'vehicle_id' => 'required|exists:vehicles,id'
        ]);

        $card = Card::findOrFail($request->card_id);
        return redirect('/validator/' . $request->vehicle_id . '?card=' . $card->card_number);
    }
}