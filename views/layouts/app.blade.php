<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $siteName ?? 'Сайт')</title>

</head>

<body>

    <header>
        <h1>{{ $siteName ?? 'Мой сайт' }}</h1>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Все права защищены</p>
    </footer>

</body>

</html>