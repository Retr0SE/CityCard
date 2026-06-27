<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City; // Підключаємо модель міст

class CityController extends Controller
{
    // 1. READ: Вивід усіх міст
    public function index()
    {
        $cities = City::all();
        // Передаємо міста у шаблон (який ми створимо пізніше)
        return view('admin.cities_index', compact('cities'));
    }

    // 2. CREATE: Збереження нового міста в базу
    public function store(Request $request)
    {
        // Додаємо правило unique:назва_таблиці,назва_колонки
        $request->validate([
            'city_name' => 'required|string|max:255|unique:cities,city_name'
        ], [
            // Кастомне повідомлення, якщо спрацює правило unique
            'city_name.unique' => 'Помилка: Таке місто вже додано до системи!'
        ]);

        // Якщо перевірка пройдена, створюємо місто
        City::create([
            'city_name' => $request->city_name
        ]);

        return back()->with('success', 'Місто успішно додано!');
    }

    // 4. DELETE: Видалення міста
    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();

        return back()->with('success', 'Місто видалено з бази!');
    }
    public function edit($id)
    {
        $city = \App\Models\City::findOrFail($id);
        return view('admin.cities_edit', compact('city'));
    }
    
    // 3. UPDATE: Оновлення існуючого міста
    public function update(Request $request, $id)
    {
        // Перевірка на унікальність виключає поточне місто (id)
        $request->validate(['city_name' => 'required|string|max:255|unique:cities,city_name,'.$id]);
        $city = \App\Models\City::findOrFail($id);
        $city->update(['city_name' => $request->city_name]);
        return redirect('/admin/cities')->with('success', 'Місто успішно оновлено!');
    }
}