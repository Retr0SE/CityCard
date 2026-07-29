@extends('admin.layout')

@section('title', 'Транспорт')
@section('header_title', 'База транспортних засобів')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
    <div class="content-card">
        <h3>Реєстрація транспорту</h3>
        <form action="/admin/vehicles" method="POST">
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

            <label style="font-weight: 500; display: block; margin-bottom: 8px;">Номер маршруту:</label>
            <input type="text" name="vehicle_number" placeholder="" required>

            <button type="submit" class="btn-primary" style="width: 100%;">Зареєструвати транспорт</button>
        </form>
    </div>

    <div class="content-card">
        <h3>Зареєстрований транспорт</h3>
        <table>
            <tr>
                <th>Місто</th>
                <th>Тип</th>
                <th>Маршрут №</th>
                <th style="text-align: right;">Дії</th>
            </tr>
            @foreach($vehicles as $vehicle)
            <tr>
                <td>{{ $vehicle->city->city_name }}</td>
                <td><span style="background: #e2e8f0; padding: 4px 8px; border-radius: 4px; font-size: 13px;">{{ $vehicle->transportType->type_name }}</span></td>
                <td><strong>{{ $vehicle->vehicle_number }}</strong></td>
                
                <td style="text-align: right; display: flex; justify-content: flex-end; gap: 15px;">
                    <button type="button" onclick="document.getElementById('edit-vehicle-{{ $vehicle->id }}').style.display='flex'" style="background: none; border: none; color: #0052d4; cursor: pointer; font-weight: bold; font-size: 14px; padding: 0;">Редагувати</button>
                    
                    <form action="/admin/vehicles/{{ $vehicle->id }}" method="POST" onsubmit="return confirm('Видалити транспорт?');">
                        @csrf @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: #e53e3e; cursor: pointer; font-weight: bold; font-size: 14px; padding: 0;">Видалити</button>
                    </form>

                    <div id="edit-vehicle-{{ $vehicle->id }}" class="modal-overlay">
                        <div class="modal-box" style="text-align: left;">
                            <button type="button" class="modal-close" onclick="document.getElementById('edit-vehicle-{{ $vehicle->id }}').style.display='none'">&times;</button>
                            <h3 style="margin-top:0; color: var(--primary-blue); border-bottom: 2px solid var(--bg-color); padding-bottom: 10px; margin-bottom: 20px;">Редагувати транспорт</h3>
                            
                            <form action="/admin/vehicles/{{ $vehicle->id }}" method="POST">
                                @csrf @method('PUT')
                                <label style="font-weight: 500; display: block; margin-bottom: 8px;">Місто:</label>
                                <select name="city_id" required>
                                    @foreach($cities as $c) 
                                        <option value="{{ $c->id }}" {{ $vehicle->city_id == $c->id ? 'selected' : '' }}>{{ $c->city_name }}</option> 
                                    @endforeach
                                </select>
                                
                                <label style="font-weight: 500; display: block; margin-bottom: 8px;">Тип:</label>
                                <select name="transport_type_id" required>
                                    @foreach($transportTypes as $t) 
                                        <option value="{{ $t->id }}" {{ $vehicle->transport_type_id == $t->id ? 'selected' : '' }}>{{ $t->type_name }}</option> 
                                    @endforeach
                                </select>

                                <label style="font-weight: 500; display: block; margin-bottom: 8px;">Номер маршруту:</label>
                                <input type="text" name="vehicle_number" value="{{ $vehicle->vehicle_number }}" required>
                                
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