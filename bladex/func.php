<?php

if (!function_exists('view')) {
    /**
     * Глобальная функция для рендеринга Blade шаблонов
     */
    function view(string $template, array $data = []): Bitrix\Main\HttpResponse
    {
        return Bladex\BladeRenderer::getInstance()->render($template, $data);
    }
}


if (!function_exists('route')) {
    function route(string $name, array $params = [])
    {
        $router = \Bitrix\Main\Application::getInstance()->getRouter();
        return $router->route($name, $params);
    }
}