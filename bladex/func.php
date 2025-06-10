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
    $fullPath = $_SERVER['DOCUMENT_ROOT'] . $filePath;

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