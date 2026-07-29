<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Card;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            

            if (Auth::user()->role === 'admin') {
                return redirect('/admin/dashboard');
            }

            return redirect('/dashboard');
        }

        return back()->withErrors(['error' => 'Невірний номер телефону або пароль!']);
    }

    public function registerUser(Request $request)
    {

        $request->validate([
            'full_name' => 'required|string|max:100',
            'phone' => 'required|digits:10|unique:users,phone',
            'password' => 'required|string|min:4|confirmed',
            'ticket_type_id' => 'required|exists:ticket_types,id'
        ], [
            'phone.unique' => 'Користувач із таким номером телефону вже існує!',
            'phone.digits' => 'Номер телефону має містити 10 цифр!',
            'ticket_type_id.required' => 'Оберіть тип квитка!'
        ]);

        $user = User::create([
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password), 
            'role' => 'user'
        ]);


        do {
            $cardNumber = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $exists = Card::where('card_number', $cardNumber)->exists();
        } while ($exists);


        Card::create([
            'user_id' => $user->id,
            'card_number' => $cardNumber,
            'balance' => 0.00,
            'ticket_type_id' => $request->ticket_type_id 
        ]);


        Auth::login($user);


        return redirect('/dashboard')->with('success', 'Акаунт успішно створено! Ваша нова віртуальна CityCard готова до використання.');
    }


    public function loginAdmin(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);


        if (Auth::attempt($credentials)) {
            

            if (Auth::user()->role === 'admin') {
                $request->session()->regenerate();

                return redirect('/admin/dashboard'); 
            } else {

                Auth::logout();
                return back()->withErrors(['error' => 'У вас немає прав адміністратора!']);
            }
        }

        return back()->withErrors(['error' => 'Невірний логін або пароль!']);
    }

    public function logout(Request $request)
    {
        $isAdmin = Auth::user() && Auth::user()->role === 'admin';

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($isAdmin) {
            return redirect('/admin/login');
        }

        return redirect('/login');
    }
}
