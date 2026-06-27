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

        // 2. Auth::attempt сам знайде користувача за телефоном і звірить хеш пароля
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Якщо випадково через цю форму зайшов адмін — перекидаємо в адмінку
            if (Auth::user()->role === 'admin') {
                return redirect('/admin/dashboard');
            }

            // Звичайного пасажира відправляємо в його кабінет
            return redirect('/dashboard');
        }

        return back()->withErrors(['error' => 'Невірний номер телефону або пароль!']);
    }

    public function registerUser(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:100',
            'phone' => 'required|string|max:15|unique:users,phone',
            'password' => 'required|string|min:4|confirmed'
        ], [
            'phone.unique' => 'Користувач із таким номером телефону вже існує!'
        ]);

        // 2. Створюємо пасажира в базі
        $user = User::create([
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            // Пароль обов'язково шифруємо
            'password' => Hash::make($request->password), 
            'role' => 'user'
        ]);

        // 3. Генеруємо випадковий 6-значний номер для його нової картки
        $cardNumber = mt_rand(100000, 999999);

        // 4. Випускаємо картку для цього користувача
        Card::create([
            'user_id' => $user->id,
            'card_number' => (string)$cardNumber,
            'balance' => 0.00
        ]);

        // 5. Автоматично авторизуємо його в системі
        Auth::login($user);

        // 6. Відправляємо в особистий кабінет з привітанням
        return redirect('/dashboard')->with('success', 'Акаунт успішно створено! Ваша нова віртуальна CityCard готова до використання.');
    }

    // Вхід для адміна (Логін + Пароль)
    public function loginAdmin(Request $request)
    {
        // 1. Перевіряємо, чи ввели логін та пароль
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
}
