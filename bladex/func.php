<?php

function view(string $template, array $data = []): Bitrix\Main\HttpResponse
{
    return Bladex\BladeRenderer::getInstance()->render($template, $data);
}

function route(string $name, array $params = [])
{
    $router = \Bitrix\Main\Application::getInstance()->getRouter();
    return $router->route($name, $params);
}
