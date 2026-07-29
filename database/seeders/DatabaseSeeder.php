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

        $this->call([
            AdminSeeder::class,
            TransportSeeder::class,
            TicketTypeSeeder::class,
        ]);
    }
}