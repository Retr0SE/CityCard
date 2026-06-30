<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\TicketPriceController;
use App\Http\Controllers\ValidatorController;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function(){
    return redirect('/login');
});

// Сторінки входу
Route::view('/login', 'auth.user_login')->name('login');
Route::view('/admin/login', 'auth.admin_login');

// Обробка форм входу
Route::post('/login', [AuthController::class, 'loginUser']);
Route::post('/admin/login', [AuthController::class, 'loginAdmin']);

// Сторінка реєстрації пасажира
Route::view('/register', 'auth.user_register')->name('register');

// Обробка форми реєстрації
Route::post('/register', [AuthController::class, 'registerUser']);

// Захищені маршрути для користувача
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    // Маршрут, який просто перекидає з кабінету на валідатор
    Route::post('/card/pay-redirect', [UserController::class, 'redirectToValidator']);
    
    // Нові маршрути для кнопок
    Route::post('/card/topup', [UserController::class, 'topUp']);
    Route::post('/card/pay', [UserController::class, 'payFare']);
});

// Захищені маршрути для адміна (можна додати перевірку ролі)
Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->prefix('admin')->group(function () {
    
    Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');
    
    Route::resource('cities', CityController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('prices', TicketPriceController::class);

});

// Імітація фізичного валідатора у транспорті
Route::get('/validator/{vehicle_id}', [ValidatorController::class, 'showTerminal']);
Route::post('/validator/{vehicle_id}/scan', [ValidatorController::class, 'processPayment']);


Route::get('/run-transport-seeder', function () {
    try {
        Artisan::call('db:seed', [
            // Вкажіть тут точну назву вашого класу сідеру
            '--class' => 'Database\\Seeders\\TransportSeeder', 
            '--force' => true
        ]);
        return "Транспорт успішно завантажено в базу!";
    } catch (\Exception $e) {
        return "Помилка: " . $e->getMessage();
    }
});

Route::get('/run-admin-seeder', function () {
    try {
        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\AdminSeeder',
            '--force' => true // Обов'язково для запуску в production на Render
        ]);
        return "Сідер адміністратора успішно виконано! Нові дані записані.";
    } catch (\Exception $e) {
        return "Помилка при виконанні сідеру: " . $e->getMessage();
    }
});