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

function useAsset(string $filePath, bool|string $domainOption = true): string|false
{
    $fullPath = useBaseDir() . $filePath;

    if (!file_exists($fullPath)) {
        return false;
    }

    $timestamp = filemtime($fullPath);
    $versionedPath = '/' . $filePath . '?v=' . $timestamp;

    if ($domainOption === false) {
        return $versionedPath;
    }

    if ($domainOption === true) {
        $request = Bitrix\Main\Context::getCurrent()->getRequest();
        $protocol = $request->isHttps() ? 'https://' : 'http://';
        $host = $request->getHttpHost();
        return $protocol . $host . $versionedPath;
    }

    return rtrim($domainOption, '/') . $versionedPath;
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