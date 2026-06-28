<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransportType;

class TransportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $transports = [
            ['type_name' => 'Автобус'],
            ['type_name' => 'Тролейбус'],
            ['type_name' => 'Поїзд'],
            ['type_name' => 'Трамвай'],
        ];

        foreach ($transports as $transport) {

            TransportType::create($transport);
        }
    }
}