<?php

function useView(string $template, array $data = []): Bitrix\Main\HttpResponse
{
    return Bladex\BladeRenderer::getInstance()->render($template, $data);
}

function useRoute(string $name, array $params = [])
{
    $router = \Bitrix\Main\Application::getInstance()->getRouter();
    return $router->route($name, $params);
}

function useUpload($filePath, $cacheDir = '/upload/cache/', $domainOption = true)
{
    $fullPath = useBaseDir() . $filePath;
    if (!file_exists($fullPath)) {
        return false;
    }

    $fileHash = md5_file($fullPath) . '_' . filemtime($fullPath);
    $pathInfo = pathinfo($filePath);

    $cachedFileName = $pathInfo['filename'] . '_' . substr($fileHash, 0, 8) . '.' . $pathInfo['extension'];
    $cachedPath = $cacheDir . $cachedFileName;
    $cachedFullPath = $_SERVER['DOCUMENT_ROOT'] . $cachedPath;
    $cacheFullDir = $_SERVER['DOCUMENT_ROOT'] . $cacheDir;

    // Создаем директорию кеша если не существует
    if (!is_dir($cacheFullDir)) {
        mkdir($cacheFullDir, 0755, true);
    }

    // Ищем и удаляем старые версии этого файла
    $baseFileName = $pathInfo['filename'];
    $extension = $pathInfo['extension'];
    $pattern = $cacheFullDir . $baseFileName . '_*.' . $extension;

    $existingFiles = glob($pattern);
    foreach ($existingFiles as $existingFile) {
        // Если найденный файл не совпадает с текущим хешем - удаляем
        if ($existingFile !== $cachedFullPath && is_file($existingFile)) {
            unlink($existingFile);
        }
    }

    // Создаем новый файл если его нет
    if (!file_exists($cachedFullPath)) {
        copy($fullPath, $cachedFullPath);
    }

    // Обработка опции домена
    if ($domainOption === false) {
        // Без домена
        return $cachedPath;
    } elseif ($domainOption === true) {
        // Автоматическое определение протокола и домена
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $domain = $_SERVER['HTTP_HOST'];
        return $protocol . $domain . $cachedPath;
    } elseif (is_string($domainOption)) {
        // Кастомный домен
        return $domainOption . $cachedPath;
    }

    return $cachedPath;
}

function useBaseDir()
{
    return $_SERVER['DOCUMENT_ROOT'] . '/local/src';
}
/**
 * Получает значение из переменных окружения (.env)
 * 
 * @param string $key Ключ переменной окружения
 * @param mixed $default Значение по умолчанию
 * @return mixed
 */
function env(string $key, mixed $default = null): mixed
{
    // Проверяем переменные окружения сначала
    $value = $_ENV[$key] ?? getenv($key);

    // Если не найдено в переменных окружения, пытаемся загрузить из .env файла
    if ($value === false) {
        static $envLoaded = false;
        static $envVars = [];

        if (!$envLoaded) {
            $envPath = dirname(__FILE__, 2) . '/.env';

            if (file_exists($envPath)) {
                $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

                foreach ($lines as $line) {
                    // Пропускаем комментарии
                    if (strpos(trim($line), '#') === 0) {
                        continue;
                    }

                    // Парсим строку KEY=VALUE
                    if (strpos($line, '=') !== false) {
                        [$envKey, $envValue] = explode('=', $line, 2);
                        $envKey = trim($envKey);
                        $envValue = trim($envValue);

                        // Убираем кавычки если есть
                        $envValue = trim($envValue, '"\'');

                        $envVars[$envKey] = $envValue;
                    }
                }
            }

            $envLoaded = true;
        }

        $value = $envVars[$key] ?? false;
    }

    if ($value === false) {
        return $default;
    }

    // Преобразование строковых значений в соответствующие типы
    return match (strtolower($value)) {
        'true', '(true)' => true,
        'false', '(false)' => false,
        'null', '(null)' => null,
        'empty', '(empty)' => '',
        default => $value
    };
}

/**
 * Получает значение из конфигурации по ключу или весь массив
 * Поддерживает загрузку из PHP файлов и точечную нотацию
 * 
 * @param array|string $config Массив конфигурации или имя файла конфигурации (без .php)
 * @param string|null $key Ключ конфигурации (поддерживает точечную нотацию). Если null - возвращает весь массив
 * @param mixed $default Значение по умолчанию, если ключ не найден
 * @return mixed
 */
function useConfig(array|string $config, ?string $key = null, mixed $default = null): mixed
{
    // Если передан путь к файлу - загружаем конфиг
    if (is_string($config)) {
        // Формируем путь к файлу в папке config
        $configPath = dirname(__FILE__, 2) . "/config/{$config}.php";

        if (!file_exists($configPath)) {
            return $default ?? null;
        }

        $loadedConfig = include $configPath;

        // Проверяем что файл вернул массив
        if (!is_array($loadedConfig)) {
            return $default ?? null;
        }

        $config = $loadedConfig;
    }

    // Если конфиг не является массивом, возвращаем дефолт
    if (!is_array($config)) {
        return $default ?? null;
    }

    // Если ключ не передан - возвращаем весь массив
    if ($key === null) {
        return $config;
    }

    // Поддержка точечной нотации для вложенных ключей
    if (strpos($key, '.') !== false) {
        $keys = explode('.', $key);
        $value = $config;

        foreach ($keys as $k) {
            if (!is_array($value) || !array_key_exists($k, $value)) {
                return $default ?? null;
            }
            $value = $value[$k];
        }

        return $value;
    }

    // Возвращаем значение по ключу или дефолт
    return $config[$key] ?? $default ?? null;
}