<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Особистий кабінет | CityCard</title>
    <style>
        :root {
            --primary-blue: #0052d4;
            --secondary-blue: #4364f7;
            --light-blue: #f0f4f8;
            --white: #ffffff;
            --text-dark: #333333;
            --text-muted: #6c757d;
            --success: #28a745;
        }

        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--light-blue);
            margin: 0;
            padding: 0;
            color: var(--text-dark);
        }

        /* Навігаційна шапка */
        .navbar {
            background-color: var(--white);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .navbar .brand { font-size: 22px; font-weight: bold; color: var(--primary-blue); }
        .navbar a { color: var(--text-muted); text-decoration: none; font-weight: bold; transition: color 0.3s; }
        .navbar a:hover { color: var(--primary-blue); }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Навігація між картками (Вкладки) */
        .card-navigation {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
            overflow-x: auto;
            padding-bottom: 15px;
            
            scrollbar-width: thin; 
            scrollbar-color: #a0bbf5 transparent; 
        }
        
        /* Стиль повзунка для Chrome, Safari, Edge, Opera */
        .card-navigation::-webkit-scrollbar { 
            height: 6px;
        }
        .card-navigation::-webkit-scrollbar-track {
            background: transparent; 
        }
        .card-navigation::-webkit-scrollbar-thumb {
            background-color: #a0bbf5; 
            border-radius: 10px; 
        }
        .card-navigation::-webkit-scrollbar-thumb:hover {
            background-color: var(--primary-blue);
        }

        .tab-btn {
            padding: 12px 20px;
            background: var(--white);
            border: 1px solid #ced4da;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            color: var(--text-muted);
            transition: all 0.3s ease;
            white-space: nowrap;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
            flex-shrink: 0; 
        }
        .tab-btn:hover { border-color: var(--primary-blue); color: var(--primary-blue); }
        .tab-btn.active {
            background: var(--primary-blue);
            color: var(--white);
            border-color: var(--primary-blue);
            box-shadow: 0 4px 10px rgba(0, 82, 212, 0.2);
        }

        .tab-btn-add {
            background: transparent;
            border: 2px dashed var(--primary-blue);
            color: var(--primary-blue);
        }
        .tab-btn-add:hover { background: rgba(0, 82, 212, 0.05); }

        /* Контейнер для приховування/показу карток */
        .card-content-wrapper { display: none; animation: fadeIn 0.4s ease; }
        .card-content-wrapper.active { display: block; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Хрестик на вкладці карток */
        .btn-tab-delete {
            background: transparent;
            border: none;
            color: inherit;
            font-size: 22px;
            cursor: pointer;
            padding: 0 12px 0 2px;
            opacity: 0.5;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }
        .btn-tab-delete:hover {
            opacity: 1;
            color: #dc3545; /* Червоний при наведенні */
        }
        /* Коли вкладка активна (синя), хрестик підлаштовується */
        .tab-btn.active .btn-tab-delete {
            color: var(--white);
            opacity: 0.7;
        }
        .tab-btn.active .btn-tab-delete:hover {
            opacity: 1;
            color: #ffcccc; /* Світло-червоний на синьому тлі */
        }

        /* --- НОВЕ: Модальне вікно для створення картки --- */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(3px);
        }
        .modal-overlay.active { display: flex; animation: fadeIn 0.2s ease; }
        .modal-box {
            background: var(--white);
            padding: 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .modal-header h3 { margin: 0; color: var(--text-dark); font-size: 20px; }
        .close-modal { cursor: pointer; font-size: 28px; color: var(--text-muted); background: none; border: none; line-height: 1; }
        .close-modal:hover { color: #dc3545; }


        /* Дизайн Транспортної Картки (Ваш оригінальний) */
        .credit-card {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: var(--white);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 20px rgba(67, 100, 247, 0.3);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .credit-card::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        /* Бейдж типу квитка на самій картці */
        .card-type-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            color: var(--white);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            z-index: 3;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; position: relative; z-index: 2; }
        .card-title { font-size: 14px; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; }
        .card-number { font-size: 26px; font-family: 'Courier New', Courier, monospace; letter-spacing: 3px; margin-bottom: 20px; position: relative; z-index: 2; text-shadow: 1px 1px 2px rgba(0,0,0,0.2); }
        
        .card-footer { display: flex; justify-content: space-between; align-items: flex-end; position: relative; z-index: 2; }
        .balance-label { font-size: 12px; opacity: 0.9; margin-bottom: 5px; }
        .balance-amount { font-size: 32px; font-weight: bold; }

        /* Панель дій */
        .action-panel { display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap; }
        .action-box { flex: 1; min-width: 250px; background: var(--white); padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.03); display: flex; flex-direction: column; }
        .action-box h4 { margin: 0 0 15px 0; color: var(--primary-blue); font-size: 18px; }

        select { padding: 12px; border: 1px solid #ced4da; border-radius: 8px; font-size: 15px; width: 100%; margin-bottom: 15px; color: var(--text-dark); outline: none; }
        select:focus { border-color: var(--secondary-blue); }
        .input-label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: var(--text-dark); }

        .btn { padding: 12px 20px; border: none; border-radius: 8px; font-size: 15px; font-weight: bold; cursor: pointer; transition: all 0.3s ease; width: 100%; text-align: center; }
        .btn-pay { background-color: var(--primary-blue); color: var(--white); }
        .btn-pay:hover { background-color: var(--secondary-blue); box-shadow: 0 4px 10px rgba(67, 100, 247, 0.3); }
        .btn-topup { background-color: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue); }
        .btn-topup:hover { background-color: var(--primary-blue); color: var(--white); }

        .quick-amounts { display: flex; gap: 10px; margin-bottom: 15px; }
        .btn-quick { flex: 1; padding: 8px 0; background: var(--light-blue); border: 1px solid #ced4da; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; color: var(--text-dark); transition: 0.2s; }
        .btn-quick:hover { border-color: var(--primary-blue); color: var(--primary-blue); background: #e6f0ff; }
        .amount-input { padding: 12px; border: 1px solid #ced4da; border-radius: 8px; font-size: 15px; width: 100%; box-sizing: border-box; margin-bottom: 15px; outline: none; }
        .amount-input:focus { border-color: var(--secondary-blue); }

        /* Історія */
        .history-panel { background: var(--white); padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.03); }
        .history-panel h3 { margin: 0 0 20px 0; color: var(--text-dark); border-bottom: 1px solid #eaeaea; padding-bottom: 15px; }
        .tx-list { list-style: none; padding: 0; margin: 0; }
        .tx-item { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #f8f9fa; }
        .tx-item:last-child { border-bottom: none; }
        .tx-info { display: flex; flex-direction: column; gap: 4px; }
        .tx-title { font-weight: 600; font-size: 16px; color: var(--text-dark); }
        .tx-desc { font-size: 14px; color: var(--text-muted); }
        .tx-date { font-size: 12px; color: #adb5bd; margin-top: 4px; }
        .tx-amount { font-weight: bold; font-size: 16px; }
        .tx-plus { color: var(--success); }
        .tx-minus { color: var(--text-dark); }

        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="brand">CityCard</div>
        <a href="/login">Вийти з кабінету</a>
    </div>

    <div class="container">
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <div class="card-navigation">
            @forelse($cards as $index => $card)
                <div class="tab-btn {{ $activeCardId == $card->id ? 'active' : '' }}" id="tab-{{ $card->id }}" style="padding: 0; display: flex; align-items: stretch; overflow: hidden;">
                    
                    <div onclick="switchCard({{ $card->id }})" style="padding: 12px 10px 12px 15px; cursor: pointer; display: flex; align-items: center;">
                        💳 Картка **{{ substr($card->card_number, -4) }}
                    </div>

                    <form action="/user/cards/{{ $card->id }}" method="POST" onsubmit="return confirm('Видалити цю картку?');" style="margin: 0; display: flex;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-tab-delete" title="Видалити картку">&times;</button>
                    </form>
                </div>
            @empty
                <div style="padding: 10px; color: var(--text-muted);">У вас ще немає жодної картки.</div>
            @endforelse
            
            <button class="tab-btn tab-btn-add" onclick="openModal()">+ Додати картку</button>
        </div>

        @foreach($cards as $index => $card)
            
            <div class="card-content-wrapper {{ $activeCardId == $card->id ? 'active' : '' }}" id="card-content-{{ $card->id }}">
                
                <div class="credit-card">
                    <div class="card-type-badge">
                        {{ $card->ticketType->name ?? 'Standard' }}
                    </div>

                    <div class="card-header">
                        <span class="card-title">Транспортна картка CityCard</span>
                        </div>
                    <div class="card-number">
                        № {{ str_pad($card->card_number, 6, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="card-footer">
                        <div>
                            <div class="balance-label">Поточний баланс</div>
                            <div class="balance-amount">{{ number_format($card->balance, 2) }} ₴</div>
                        </div>
                    </div>
                </div>

                <div class="action-panel">
                    <div class="action-box">
                        <h4>Оплата проїзду</h4>
                        <form action="/card/pay-redirect" method="POST" style="display: flex; flex-direction: column; height: 100%;">
                            @csrf
                            <input type="hidden" name="card_id" value="{{ $card->id }}"> <select name="vehicle_id" required>
                                <option value="" disabled selected>Оберіть маршрут...</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}">
                                        {{ $vehicle->transportType->type_name }} №{{ $vehicle->vehicle_number }} ({{ $vehicle->city->city_name }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-pay" style="margin-top: auto;">Оплатити квиток</button>
                        </form>
                    </div>

                    <div class="action-box">
                        <h4>Поповнення</h4>
                        <form action="/card/topup" method="POST" style="display: flex; flex-direction: column; height: 100%;">
                            @csrf
                            <input type="hidden" name="card_id" value="{{ $card->id }}">
                            <div class="quick-amounts">
                                <button type="button" class="btn-quick" onclick="document.getElementById('amountInput-{{ $card->id }}').value = 50">50 ₴</button>
                                <button type="button" class="btn-quick" onclick="document.getElementById('amountInput-{{ $card->id }}').value = 100">100 ₴</button>
                                <button type="button" class="btn-quick" onclick="document.getElementById('amountInput-{{ $card->id }}').value = 200">200 ₴</button>
                            </div>
                            <input type="number" id="amountInput-{{ $card->id }}" name="amount" class="amount-input" placeholder="Введіть іншу суму (₴)..." required min="1" max="10000" step="1">
                            <button type="submit" class="btn btn-topup" style="margin-top: auto;">Поповнити картку</button>
                        </form>
                    </div>
                </div>

                <div class="history-panel">
                    <h3>Останні транзакції (Картка **{{ substr($card->card_number, -4) }})</h3>
                    <ul class="tx-list">
                        @forelse($card->transactions as $txIndex => $transaction)
                            <li class="tx-item {{ $txIndex >= 5 ? 'extra-tx-' . $card->id : '' }}" style="{{ $txIndex >= 5 ? 'display: none;' : '' }}">
                                <div class="tx-info">
                                    <span class="tx-title">
                                        {{ $transaction->transaction_type == 'TOPUP' ? 'Поповнення балансу' : 'Оплата проїзду' }}
                                    </span>
                                    @if($transaction->transaction_type == 'USE' && $transaction->vehicle)
                                        <span class="tx-desc">
                                            {{ $transaction->vehicle->transportType->type_name }} №{{ $transaction->vehicle->vehicle_number }} ({{ $transaction->vehicle->city->city_name }})
                                        </span>
                                    @endif
                                    <span class="tx-date">{{ $transaction->created_at->format('d.m.Y H:i') }}</span>
                                </div>
                                <div class="tx-amount {{ $transaction->transaction_type == 'TOPUP' ? 'tx-plus' : 'tx-minus' }}">
                                    {{ $transaction->transaction_type == 'TOPUP' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} ₴
                                </div>
                            </li>
                        @empty
                            <li style="text-align: center; color: #adb5bd; padding: 20px;">Історія транзакцій порожня.</li>
                        @endforelse
                    </ul>
 
                    @if($card->transactions->count() > 5)
                        <div style="text-align: center; margin-top: 20px; padding-top: 15px; border-top: 1px solid #f8f9fa;">
                            <button type="button" id="btn-more-{{ $card->id }}" onclick="toggleHistory({{ $card->id }})" style="background: none; border: none; color: var(--primary-blue); font-weight: bold; font-size: 15px; cursor: pointer;">
                                Показати більше ↓
                            </button>
                        </div>
                    @endif
                </div>
            </div> @endforeach
    </div>

    <div class="modal-overlay" id="addCardModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Створити нову картку</h3>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            
            <form action="{{ route('user.cards.store') ?? '/user/cards' }}" method="POST">
                @csrf
                
                <label for="ticket_type" class="input-label">Оберіть тариф (тип квитка):</label>
                <select name="ticket_type_id" id="ticket_type" required>
                    <option value="" disabled selected>Виберіть тариф...</option>
                    @if(isset($ticketTypes))
                        @foreach($ticketTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    @else
                        <option value="1">Звичайний</option>
                        <option value="2">Студентський</option>
                    @endif
                </select>

                <button type="submit" class="btn btn-pay" style="margin-top: 10px;">Створити картку</button>
            </form>
        </div>
    </div>

    <script>
        // Функція для перемикання вкладок карток
        function switchCard(cardId) {
            // Ховаємо весь контент карток
            document.querySelectorAll('.card-content-wrapper').forEach(el => {
                el.classList.remove('active');
            });
            // Знімаємо підсвітку з усіх кнопок-вкладок
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('active');
            });

            // Показуємо обрану картку та підсвічуємо кнопку
            document.getElementById('card-content-' + cardId).classList.add('active');
            document.getElementById('tab-' + cardId).classList.add('active');
        }

        // Функції для модального вікна
        function openModal() {
            document.getElementById('addCardModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('addCardModal').classList.remove('active');
        }

        // Закриття модалки при кліку на темний фон
        document.getElementById('addCardModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // Ваша оригінальна функція історії
        function toggleHistory(cardId) {
            const items = document.querySelectorAll('.extra-tx-' + cardId);
            const btn = document.getElementById('btn-more-' + cardId);
            let isShowingAll = btn.getAttribute('data-showing') === 'true';
            
            items.forEach(item => {
                item.style.display = isShowingAll ? 'none' : 'flex'; 
            });
            
            if (isShowingAll) {
                btn.innerText = 'Показати більше ↓';
                btn.setAttribute('data-showing', 'false');
            } else {
                btn.innerText = 'Приховати ↑';
                btn.setAttribute('data-showing', 'true');
            }
        }
    </script>
</body>
</html>