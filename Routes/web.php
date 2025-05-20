<?php

use Bitrix\Main\Routing\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->get('/test', function () {
        return "/ test2";
    });

    $routes->get('/my', [\App\Controllers\HomeController::class, 'index']);

    // Другие маршруты
    $routes->get('/about', [\App\Controllers\AboutController::class, 'index']);

    // Группировка маршрутов
    $routes->prefix('/api')->group(function (RoutingConfigurator $routes) {
        $routes->get('/users', [\App\Controllers\Api\UserController::class, 'list']);
        $routes->post('/users', [\App\Controllers\Api\UserController::class, 'create']);
    });
};