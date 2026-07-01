<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketType;

class TicketTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Стандартний'],
            ['name' => 'Студентський'],
            ['name' => 'Пільговий'],
            ['name' => 'Учнівський'],
        ];

        foreach ($types as $type) {

            TicketType::firstOrCreate(
                ['name' => $type['name']], 
                $type
            );
        }
    }
}