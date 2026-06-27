<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід у кабінет | CityCard</title>
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

        .login-container {
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

        .btn-login {
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

        .btn-login:hover {
            opacity: 0.9;
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .register-link a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
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

    <div class="login-container">
        <div class="logo">CityCard</div>
        <div class="subtitle">Вхід до особистого кабінету пасажира</div>

        @if($errors->any())
            <div class="error-box">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Номер телефону</label>
                <input type="text" name="phone" placeholder="Введіть номер телефону" required>
            </div>

            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password" placeholder="Введіть пароль" required>
            </div>

            <button type="submit" class="btn-login">Увійти в кабінет</button>
        </form>

        <div class="register-link">
            Ще не маєте електронної картки? <br><br>
            <a href="/register">Зареєструватися та отримати картку</a>
        </div>
    </div>

</body>
</html>