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
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <th style="text-align: left; padding: 10px; border-bottom: 2px solid #eaeaea;">ID</th>
                <th style="text-align: left; padding: 10px; border-bottom: 2px solid #eaeaea;">Назва міста</th>
                <th style="text-align: right; padding: 10px; border-bottom: 2px solid #eaeaea;">Дії</th>
            </tr>
            @foreach($cities as $city)
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eaeaea;"><strong>#{{ $city->id }}</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #eaeaea;">{{ $city->city_name }}</td>
                
                <td style="padding: 10px; border-bottom: 1px solid #eaeaea; text-align: right; display: flex; justify-content: flex-end; gap: 15px;">
                    <button type="button" onclick="document.getElementById('edit-city-{{ $city->id }}').style.display='flex'" style="background: none; border: none; color: #0052d4; cursor: pointer; font-weight: bold; font-size: 14px; padding: 0;">Редагувати</button>
                    
                    <form action="/admin/cities/{{ $city->id }}" method="POST" onsubmit="return confirm('Видалити місто?');">
                        @csrf @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: #e53e3e; cursor: pointer; font-weight: bold; font-size: 14px; padding: 0;">Видалити</button>
                    </form>

                    <div id="edit-city-{{ $city->id }}" class="modal-overlay" style="display: none;">
                        <div class="modal-box" style="text-align: left;">
                            <button type="button" class="modal-close" onclick="document.getElementById('edit-city-{{ $city->id }}').style.display='none'" style="float: right; border: none; background: none; font-size: 20px; cursor: pointer;">&times;</button>
                            <h3 style="margin-top:0; color: #0052d4; border-bottom: 1px solid #eaeaea; padding-bottom: 10px; margin-bottom: 20px;">Редагувати місто</h3>
                            
                            <form action="/admin/cities/{{ $city->id }}" method="POST">
                                @csrf @method('PUT')
                                <label style="font-weight: 500; font-size: 14px; margin-bottom: 8px; display: block;">Назва міста:</label>
                                <input type="text" name="city_name" value="{{ $city->city_name }}" required style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ced4da; border-radius: 6px; box-sizing: border-box;">
                                
                                <button type="submit" class="btn-primary" style="width: 100%;">Зберегти зміни</button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection