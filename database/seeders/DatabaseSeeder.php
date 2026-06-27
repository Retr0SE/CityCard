<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\City;
use App\Models\TransportType;
use App\Models\Card;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Створюємо Головного Адміністратора
        User::create([
            'full_name' => 'Головний Адміністратор',
            'login' => 'admin',
            'password' => Hash::make('12345'), 
            'role' => 'admin',
        ]);

        // 2. Створюємо Тестового Пасажира
        $testUser = User::create([
            'full_name' => 'Іван Петренко',
            'phone' => '0991234567',
            'role' => 'user',
        ]);

        // Даємо йому картку з балансом 150 грн
        Card::create([
            'user_id' => $testUser->id,
            'card_number' => '1001',
            'balance' => 150.00,
        ]);

        // 3. Додаємо базові міста
        City::create(['city_name' => 'Луцьк']);
        City::create(['city_name' => 'Рівне']);

        // 4. Додаємо типи транспорту
        TransportType::create(['type_name' => 'Тролейбус']);
        TransportType::create(['type_name' => 'Автобус']);
    }
}