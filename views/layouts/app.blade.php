<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $siteName ?? 'Сайт')</title>
    
    @showHead
    
    @css('/local/assets/css/main.css')
    @css('/local/assets/css/custom.css')
    
    @stack('styles')
</head>
<body>
    @showPanel
    
    <header>
        <h1>{{ $siteName ?? 'Мой сайт' }}</h1>
        
        @ifAuth
            <p>Добро пожаловать, {{ $USER->GetFirstName() }}!</p>
        @else
            <p><a href="/auth/">Войти</a></p>
        @endifAuth
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Все права защищены</p>
    </footer>

    @js('/local/assets/js/main.js')
    @stack('scripts')
</body>
</html>