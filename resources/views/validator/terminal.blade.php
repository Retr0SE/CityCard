<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Валідатор | {{ $vehicle->vehicle_number }}</title>
    <style>
        :root {
            --primary-blue: #0052d4;
            --secondary-blue: #4364f7;
            --light-bg: #f0f4f8;
            --white: #ffffff;
            --text-dark: #333;
            --success: #28a745;
            --error: #e53e3e;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--light-bg);
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: var(--text-dark);
        }

        /* Шапка */
        .header { text-align: center; margin-bottom: 40px; }
        .city-name { color: var(--primary-blue); font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .route-info { font-size: 32px; font-weight: 800; color: var(--text-dark); }

        /* Картка валідатора */
        .terminal-card {
            background: var(--white);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            text-align: center;
            width: 90%;
            max-width: 400px;
        }

        .status-icon { font-size: 60px; margin-bottom: 20px; }
        
        .message { font-size: 24px; font-weight: 600; margin-bottom: 20px; }
        .balance-info { font-size: 18px; color: var(--text-dark); opacity: 0.8; }

        /* Поле вводу */
        .scan-input {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 18px;
            box-sizing: border-box;
            text-align: center;
            outline: none;
        }
        .scan-input:focus { border-color: var(--primary-blue); }

        .btn-ok {
            background: var(--primary-blue);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
        }
        .btn-ok:hover { background: var(--secondary-blue); }

        .back-link { margin-top: 30px; color: var(--primary-blue); text-decoration: none; font-weight: 600; }

        /* Кольори статусу */
        .status-success { color: var(--success); }
        .status-error { color: var(--error); }
    </style>
</head>
<body>

    <div class="header">
        <div class="city-name">{{ $vehicle->city->city_name }}</div>
        <div class="route-info">{{ $vehicle->transportType->type_name }} №{{ $vehicle->vehicle_number }}</div>
    </div>

    <div class="terminal-card">
        @if(session('status') === 'success')
            <div class="status-icon status-success">✅</div>
            <div class="message">{{ session('success') }}</div>
            <div class="balance-info">Залишок: {{ number_format(session('balance'), 2) }} ₴</div>
        @elseif(session('status') === 'error')
            <div class="status-icon status-error">❌</div>
            <div class="message">{{ session('error') }}</div>
        @else
            <div class="status-icon">💳</div>
            <div class="message">Прикладіть картку</div>
        @endif

        <form action="/validator/{{ $vehicle->id }}/scan" method="POST">
            @csrf
            <input type="text" name="card_number" class="scan-input" placeholder="Номер картки..." value="{{ request('card') }}" required autofocus autocomplete="off">
            <button type="submit" class="btn-ok">ОК</button>
        </form>
    </div>

    <a href="/dashboard" class="back-link">← Повернутися в кабінет</a>

    @if(session('status'))
    <script>
        setTimeout(function() {
            window.location.href = '/validator/{{ $vehicle->id }}';
        }, 3000);
    </script>
    @endif

</body>
</html>