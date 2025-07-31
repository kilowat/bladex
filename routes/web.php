<?php

use App\Exceptions\AppError;
use App\Exceptions\AppException;
use Bitrix\Main\Routing\RoutingConfigurator;

return function (RoutingConfigurator $routes) {

    $routes->get('/', [Controllers\PageController::class, 'index'])
        ->name('home');

    $routes->get('/about', [Controllers\PageController::class, 'about'])
        ->name('about');

    $routes->get('/shop', [Controllers\ShopController::class, 'index'])
        ->name('shop.index');

    $routes->get('{path}', [Controllers\PageController::class, 'default'])
        ->name('default')
        ->where('path', '.*');
    ;
};
