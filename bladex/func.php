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

    // Создаём хеш-файл
    $fileHash = md5_file($fullPath) . '_' . filemtime($fullPath);
    $pathInfo = pathinfo($filePath);

    // Генерируем имя кэшированного файла
    $cachedFileName = $pathInfo['filename'] . '_' . substr($fileHash, 0, 8) . '.' . $pathInfo['extension'];
    $cachedPath = rtrim($cacheDir, '/') . '/' . $cachedFileName;
    $cachedFullPath = $_SERVER['DOCUMENT_ROOT'] . $cachedPath;
    $cacheFullDir = $_SERVER['DOCUMENT_ROOT'] . rtrim($cacheDir, '/');

    // Создаём папку кэша, если не существует
    if (!is_dir($cacheFullDir)) {
        mkdir($cacheFullDir, 0755, true);
    }

    // Копируем файл в кэш, если его ещё нет
    if (!file_exists($cachedFullPath)) {
        copy($fullPath, $cachedFullPath);
    }

    // Возвращаем путь в зависимости от опции домена
    if ($domainOption === false) {
        return $cachedPath;
    } elseif ($domainOption === true) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $domain = $_SERVER['HTTP_HOST'];
        return $protocol . $domain . $cachedPath;
    } elseif (is_string($domainOption)) {
        return rtrim($domainOption, '/') . $cachedPath;
    }

    return $cachedPath;
}

function useBaseDir($path = '')
{
    $path = !empty($path) ? '/' . $path : $path;
    return dirname(__FILE__, 2) . $path . '/';
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
    if (is_string($config)) {
        $configPath = dirname(__FILE__, 2) . "/config/{$config}.php";

        if (!file_exists($configPath)) {
            return $default ?? null;
        }

        $loadedConfig = include $configPath;

        if (!is_array($loadedConfig)) {
            return $default ?? null;
        }

        $config = $loadedConfig;
    }

    if (!is_array($config)) {
        return $default ?? null;
    }

    if ($key === null) {
        return $config;
    }

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

    return $config[$key] ?? $default ?? null;
}