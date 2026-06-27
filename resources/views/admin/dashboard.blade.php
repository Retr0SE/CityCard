@extends('admin.layout')

@section('title', 'Головна')
@section('header_title', 'Огляд системи')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
    <div class="content-card" style="border-top: 4px solid #48bb78;">
        <h3>Міста та локації</h3>
        <p style="color: #718096; line-height: 1.5; margin-bottom: 20px;">Керування областями та містами, що підключені до єдиної електронної системи оплати.</p>
        <a href="/admin/cities" class="btn-primary" style="text-decoration: none; display: inline-block;">Відкрити реєстр →</a>
    </div>
    <div class="content-card" style="border-top: 4px solid #ecc94b;">
        <h3>Рухомий склад</h3>
        <p style="color: #718096; line-height: 1.5; margin-bottom: 20px;">Реєстрація нових транспортних засобів та прив'язка їх до міст.</p>
        <a href="/admin/vehicles" class="btn-primary" style="text-decoration: none; display: inline-block;">Керування транспортом →</a>
    </div>
    <div class="content-card" style="border-top: 4px solid #0052d4;">
        <h3>Тарифна політика</h3>
        <p style="color: #718096; line-height: 1.5; margin-bottom: 20px;">Налаштування вартості проїзду для різних типів транспорту.</p>
        <a href="/admin/prices" class="btn-primary" style="text-decoration: none; display: inline-block;">Налаштувати тарифи →</a>
    </div>
</div>
@endsection