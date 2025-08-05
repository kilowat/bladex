<?php

use Bladex\View;

function useView($view = ''): View
{
    return \Bladex\BladeRenderer::getInstance()->make($view);
}

function useCurrentRoute()
{
    return \Bitrix\Main\Application::getInstance()->getCurrentRoute();
}

function useRoute($name, $parameters = []): string
{
    return \Bitrix\Main\Application::getInstance()->getRouter()->route($name, $parameters);
}

function useContainer(): DI\Container
{
    return \Bladex\ContainerFactory::getContainer();

}

function usedd(...$vars)
{
    echo '<style>pre {background: #f5f5f5; padding: 10px; border: 1px solid #ccc;}</style>';
    echo '<pre>';
    foreach ($vars as $var) {
        if (function_exists('xdebug_var_dump')) {
            xdebug_var_dump($var);
        } else {
            var_dump($var);
        }
    }
    echo '</pre>';
    die();
}
function useCss($paths)
{
    $__paths = is_array($paths) ? $paths : [$paths];
    foreach ($__paths as $path) {
        \Bitrix\Main\Page\Asset::getInstance()->addCss('/' . ltrim(trim($path, "\\"), '/'));
    }
}

function useJs($paths)
{
    $__paths = is_array($paths) ? $paths : [$paths];
    foreach ($__paths as $path) {
        \Bitrix\Main\Page\Asset::getInstance()->addJs('/' . ltrim(trim($path, "\\"), '/'));
    }
}

function useAsset(string $filePath, bool|string $domainOption = true): string|false
{
    $fullPath = useBaseDir($filePath);

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
    return dirname(__FILE__, 2) . $path;
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