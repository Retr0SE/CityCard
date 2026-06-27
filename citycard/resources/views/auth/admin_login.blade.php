<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід для Адміністратора</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #343a40; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.5); width: 300px; text-align: center; border-top: 5px solid #dc3545; }
        input { width: 90%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { width: 100%; padding: 10px; background: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; }
        button:hover { background: #c82333; }
        .error { color: red; font-size: 14px; margin-bottom: 10px; }
    </style>
</head>
<body>

    <div class="login-box">
        <h2>Панель керування</h2>
        <p style="color: #6c757d;">Вхід для адміністраторів</p>

        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form action="/admin/login" method="POST">
            @csrf
            <input type="text" name="login" placeholder="Логін" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Увійти в систему</button>
        </form>
    </div>

</body>
</html>