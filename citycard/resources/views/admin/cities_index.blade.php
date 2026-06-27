@extends('admin.layout')

@section('title', 'Міста')
@section('header_title', 'Реєстр міст')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
    <div class="content-card">
        <h3>Додати нове місто</h3>
        <form action="/admin/cities" method="POST">
            @csrf
            <label style="font-weight: 500; font-size: 14px; margin-bottom: 8px; display: block;">Назва міста:</label>
            <input type="text" name="city_name" placeholder="Наприклад: Київ" required>
            <button type="submit" class="btn-primary" style="width: 100%;">Зберегти місто</button>
        </form>
    </div>

    <div class="content-card">
        <h3>Список підключених міст</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Назва міста</th>
                <th style="text-align: right;">Дії</th>
            </tr>
            @foreach($cities as $city)
            <tr>
                <td><strong>#{{ $city->id }}</strong></td>
                <td>{{ $city->city_name }}</td>
                
                <td style="text-align: right;">
                    <form action="/admin/cities/{{ $city->id }}" method="POST" onsubmit="return confirm('Видалити місто?');">
                        @csrf @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: #e53e3e; cursor: pointer; font-weight: bold; font-size: 14px; padding: 0;">Видалити</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection