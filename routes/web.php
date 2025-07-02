<?php

use Bitrix\Main\Routing\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->any('/', [Controllers\HomeController::class, 'index'])->name('home');
    $routes->get('/test', function () {
        return 'test';
    })->name('test');
};
