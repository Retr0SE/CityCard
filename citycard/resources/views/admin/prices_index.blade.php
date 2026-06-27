@extends('admin.layout')

@section('title', 'Тарифи')
@section('header_title', 'Керування тарифами проїзду')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
    <div class="content-card">
        <h3>Встановити тариф</h3>
        <form action="/admin/prices" method="POST">
            @csrf
            <label style="font-weight: 500; display: block; margin-bottom: 8px;">Місто:</label>
            <select name="city_id" required>
                <option value="" disabled selected>Оберіть місто...</option>
                @foreach($cities as $city) <option value="{{ $city->id }}">{{ $city->city_name }}</option> @endforeach
            </select>

            <label style="font-weight: 500; display: block; margin-bottom: 8px;">Тип транспорту:</label>
            <select name="transport_type_id" required>
                <option value="" disabled selected>Оберіть тип...</option>
                @foreach($transportTypes as $type) <option value="{{ $type->id }}">{{ $type->type_name }}</option> @endforeach
            </select>

            <label style="font-weight: 500; display: block; margin-bottom: 8px;">Вартість квитка (₴):</label>
            <input type="number" name="price" step="0.01" placeholder="Наприклад: 10.00" required>

            <button type="submit" class="btn-primary" style="width: 100%;">Зберегти тариф</button>
        </form>
    </div>

    <div class="content-card">
        <h3>Поточна сітка тарифів</h3>
        <table>
            <tr>
                <th>Місто</th>
                <th>Транспорт</th>
                <th>Вартість</th>
                <th style="text-align: right;">Дії</th>
            </tr>
            @foreach($prices as $price)
            <tr>
                <td>{{ $price->city->city_name }}</td>
                <td>{{ $price->transportType->type_name }}</td>
                <td style="color: #0052d4; font-weight: bold; font-size: 16px;">{{ number_format($price->price, 2) }} ₴</td>
                
                <td style="text-align: right; display: flex; justify-content: flex-end; gap: 15px;">
                    <button type="button" onclick="document.getElementById('edit-price-{{ $price->id }}').style.display='flex'" style="background: none; border: none; color: #0052d4; cursor: pointer; font-weight: bold; font-size: 14px; padding: 0;">Редагувати</button>
                    
                    <form action="/admin/prices/{{ $price->id }}" method="POST" onsubmit="return confirm('Видалити тариф?');">
                        @csrf @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: #e53e3e; cursor: pointer; font-weight: bold; font-size: 14px; padding: 0;">Видалити</button>
                    </form>

                    <div id="edit-price-{{ $price->id }}" class="modal-overlay">
                        <div class="modal-box" style="text-align: left;">
                            <button type="button" class="modal-close" onclick="document.getElementById('edit-price-{{ $price->id }}').style.display='none'">&times;</button>
                            <h3 style="margin-top:0; color: var(--primary-blue); border-bottom: 2px solid var(--bg-color); padding-bottom: 10px; margin-bottom: 20px;">Редагувати тариф</h3>
                            
                            <form action="/admin/prices/{{ $price->id }}" method="POST">
                                @csrf @method('PUT')
                                <label style="font-weight: 500; display: block; margin-bottom: 8px;">Місто:</label>
                                <select name="city_id" required>
                                    @foreach($cities as $c) 
                                        <option value="{{ $c->id }}" {{ $price->city_id == $c->id ? 'selected' : '' }}>{{ $c->city_name }}</option> 
                                    @endforeach
                                </select>
                                
                                <label style="font-weight: 500; display: block; margin-bottom: 8px;">Тип транспорту:</label>
                                <select name="transport_type_id" required>
                                    @foreach($transportTypes as $t) 
                                        <option value="{{ $t->id }}" {{ $price->transport_type_id == $t->id ? 'selected' : '' }}>{{ $t->type_name }}</option> 
                                    @endforeach
                                </select>

                                <label style="font-weight: 500; display: block; margin-bottom: 8px;">Вартість (₴):</label>
                                <input type="number" step="0.01" name="price" value="{{ $price->price }}" required>
                                
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