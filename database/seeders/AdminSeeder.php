<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;                  
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'full_name' => 'Головний Адміністратор',
            'login' => 'admin',
            'password' => Hash::make('super_secure_password'), // пароль обов'язково хешується
            'role' => 'admin',
        ]);
    }
}
