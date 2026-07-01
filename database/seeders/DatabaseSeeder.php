<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\City;
use App\Models\TransportType;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['login' => 'admin'],
            [
                'full_name' => 'Головний Адміністратор',
                'password' => Hash::make('12345'), 
                'role' => 'admin',
            ]
        );

        $cities = ['Луцьк', 'Рівне'];
        foreach ($cities as $cityName) {
            City::updateOrCreate(['city_name' => $cityName]);
        }

        $types = ['Тролейбус', 'Автобус'];
        foreach ($types as $typeName) {
            TransportType::updateOrCreate(['type_name' => $typeName]);
        }

        $this->call([
            AdminSeeder::class,
            TransportSeeder::class,
            TicketTypeSeeder::class,
        ]);
    }
}