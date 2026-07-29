<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Реєстрація | CityCard</title>
    <style>
        :root {
            --primary-blue: #0052d4;
            --secondary-blue: #4364f7;
            --light-blue: #f0f4f8;
            --white: #ffffff;
            --text-dark: #333333;
            --text-muted: #6c757d;
        }

        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--light-blue);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: var(--text-dark);
        }

        .register-container {
            background: var(--white);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 400px;
        }

        .logo {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: var(--primary-blue);
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: var(--text-muted);
            margin-bottom: 30px;
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            font-size: 15px;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: var(--secondary-blue);
        }

        .btn-register {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.3s;
            margin-top: 10px;
        }

        .btn-register:hover {
            opacity: 0.9;
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .login-link a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .error-box {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="register-container">
        <div class="logo">CityCard</div>
        <div class="subtitle">Отримайте свою електронну картку</div>

        @if($errors->any())
            <div class="error-box">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/register" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Прізвище та Ім'я</label>
                <input type="text" name="full_name" placeholder="" required value="{{ old('full_name') }}">
            </div>

            <div class="form-group">
                <label>Номер телефону</label>
                <input type="tel" name="phone" placeholder="" pattern="[0-9]{10}" required value="{{ old('phone') }}">
            </div>

            <div class="form-group">
                <label>Оберіть тип квитка:</label>
                <select name="ticket_type_id" required style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 8px; font-size: 15px;">
                    <option value="" disabled selected>Виберіть тариф...</option>
                    @foreach(\App\Models\TicketType::all() as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Введіть пароль</label>
                <input type="password" name="password" placeholder="Мінімум 4 символи" required>
            </div>

            <div class="form-group">
                <label>Повторіть пароль</label>
                <input type="password" name="password_confirmation" placeholder="Підтвердження пароля" required>
            </div>

            <button type="submit" class="btn-register">Зареєструватися</button>
        </form>

        <div class="login-link">
            Вже маєте картку? <a href="/login">Увійти</a>
        </div>
    </div>

</body>
</html>