<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Ошибка Bitrix</title>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: sans-serif;
            color: #333;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px 30px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .error-title {
            color: #b00020;
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .error-code {
            font-size: 0.95em;
            color: #666;
            margin-bottom: 15px;
        }

        .error-data {
            background: #f9f9f9;
            padding: 10px;
            font-family: monospace;
            font-size: 0.9em;
            border: 1px solid #ddd;
            border-radius: 4px;
            white-space: pre-wrap;
        }
    </style>
</head>

<body>
    <div class="container">
        @foreach ($errors as $error)
            <div class="error-title">{{ $error->getMessage() }}</div>

            <div class="error-code">Код ошибки: {{ $error->getCode() }}</div>

            @if ($error->getCustomData())
                <div>
                    <strong>Дополнительные данные:</strong>
                    <div class="error-data">
                        {{ json_encode($error->getCustomData(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                    </div>
                </div>
            @endif
            <br>
            <hr>
        @endforeach
    </div>
</body>

</html>