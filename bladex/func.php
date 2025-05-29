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