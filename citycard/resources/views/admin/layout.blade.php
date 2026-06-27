<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | CityCard Pro</title>
    <style>
        :root {
            --primary-blue: #0052d4; --secondary-blue: #4364f7;
            --bg-color: #f0f4f8; --sidebar-bg: #ffffff;
            --text-dark: #2d3748; --text-muted: #718096; --border-color: #e2e8f0;
        }
        body { font-family: 'Segoe UI', Roboto, sans-serif; margin: 0; background-color: var(--bg-color); display: flex; color: var(--text-dark); min-height: 100vh; }
        
        .sidebar { width: 260px; background-color: var(--sidebar-bg); box-shadow: 2px 0 15px rgba(0,0,0,0.05); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 10; }
        .sidebar-brand { padding: 25px 20px; font-size: 24px; font-weight: 800; color: var(--primary-blue); text-align: center; border-bottom: 1px solid var(--border-color); }
        .sidebar-menu { padding: 20px 0; flex-grow: 1; }
        .sidebar-menu a { display: flex; align-items: center; padding: 15px 25px; color: var(--text-muted); text-decoration: none; font-weight: 600; font-size: 15px; border-left: 4px solid transparent; }
        .sidebar-menu a:hover { background-color: #f8fafc; color: var(--primary-blue); border-left-color: var(--primary-blue); }
        .sidebar-footer { padding: 20px; border-top: 1px solid var(--border-color); }
        .btn-logout { display: block; text-align: center; padding: 12px; background-color: #fff5f5; color: #e53e3e; text-decoration: none; border-radius: 8px; font-weight: bold; }
        
        .main-content { margin-left: 260px; padding: 30px 40px; width: calc(100% - 260px); box-sizing: border-box; }
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .page-title { margin: 0; font-size: 28px; font-weight: 700; }
        .admin-profile { background: var(--sidebar-bg); padding: 8px 16px; border-radius: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); font-weight: 600; color: var(--primary-blue); }
        
        .content-card { background: var(--sidebar-bg); padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 30px; }
        .content-card h3 { margin-top: 0; color: var(--primary-blue); margin-bottom: 20px; border-bottom: 2px solid var(--bg-color); padding-bottom: 10px; }
        
        input, select { padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; width: 100%; box-sizing: border-box; margin-bottom: 15px; outline: none; font-size: 15px; }
        input:focus, select:focus { border-color: var(--secondary-blue); }
        .btn-primary { background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue)); color: white; padding: 12px 20px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 15px; }
        .btn-primary:hover { opacity: 0.9; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid var(--border-color); }
        th { background-color: #f8fafc; color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 13px; }
        td { color: var(--text-dark); font-size: 15px; }
        
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-error { background-color: #f8d7da; color: #721c24; }

        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 100; justify-content: center; align-items: center; }
        .modal-box { background: white; padding: 30px; border-radius: 12px; width: 100%; max-width: 400px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); position: relative; }
        .modal-close { position: absolute; top: 15px; right: 15px; cursor: pointer; font-size: 24px; font-weight: bold; color: #718096; border: none; background: none; line-height: 1; }
        .modal-close:hover { color: #e53e3e; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand">CityCard Pro</div>
        <div class="sidebar-menu">
            <a href="/admin/dashboard">❖ Головна панель</a>
            <a href="/admin/cities">🏙 Керування містами</a>
            <a href="/admin/vehicles">🚌 Транспортна база</a>
            <a href="/admin/prices">💳 Тарифи проїзду</a>
        </div>
        <div class="sidebar-footer">
            <a href="/login" class="btn-logout">Завершити роботу</a>
        </div>
    </div>

    <div class="main-content">
        <div class="top-header">
            <h1 class="page-title">@yield('header_title')</h1>
            <div class="admin-profile">👤 Адміністратор</div>
        </div>

        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="alert alert-error">{{ $errors->first() }}</div> @endif

        @yield('content')
    </div>

</body>
</html>