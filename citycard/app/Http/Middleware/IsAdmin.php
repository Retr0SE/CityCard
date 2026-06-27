<?php

namespace App\Http\Middleware;

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Перевіряємо: чи людина авторизована І чи є вона адміном
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Пропускаємо далі
        }

        // Інакше — перекидаємо на головну сторінку з помилкою 403 (Заборонено)
        abort(403, 'Доступ заборонено! Ця сторінка тільки для адміністраторів.');
    }
}