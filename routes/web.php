<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\TicketPriceController;
use App\Http\Controllers\ValidatorController;
use App\Http\Controllers\CardController;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function(){
    return redirect('/login');
});

// login pages
Route::view('/login', 'auth.user_login')->name('login');
Route::view('/admin/login', 'auth.admin_login');

// handling login forms
Route::post('/login', [AuthController::class, 'loginUser']);
Route::post('/admin/login', [AuthController::class, 'loginAdmin']);
// logout page
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// passenger registration page
Route::view('/register', 'auth.user_register')->name('register');
// handling registration page
Route::post('/register', [AuthController::class, 'registerUser']);

// protected routes for user
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    // A route that redirects the user from the dashboard to the validator
    Route::post('/card/pay-redirect', [UserController::class, 'redirectToValidator']);
    // new routes for buttons
    Route::post('/card/topup', [UserController::class, 'topUp']);
    Route::post('/card/pay', [UserController::class, 'payFare']);
    Route::post('/user/cards', [CardController::class, 'store'])->name('user.cards.store');
    Route::delete('/user/cards/{id}', [App\Http\Controllers\CardController::class, 'destroy']);
});

// protected routes for admin
Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->prefix('admin')->group(function () {
    
    Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');
    
    Route::resource('cities', CityController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('prices', TicketPriceController::class);

});

// simulation of a validator on public transport
Route::get('/validator/{vehicle_id}', [ValidatorController::class, 'showTerminal']);
Route::post('/validator/{vehicle_id}/scan', [ValidatorController::class, 'processPayment']);


Route::get('/sync-database', function () {
    try {
        // 1. create tables
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        
        // 2. fill tables with data
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        
        return "База даних успішно створена та синхронізована!";
    } catch (\Exception $e) {
        return "Помилка: " . $e->getMessage();
    }
});