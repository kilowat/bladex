<?php

use Bitrix\Main\Routing\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->get('/', [Controllers\PageController::class, 'index'])
        ->name('home');

    $routes->get('/shop', [Controllers\ShopController::class, 'index'])
        ->name('shop.index');

    $routes->get('/{page}', [Controllers\PageController::class, 'show'])
        ->where('page', '.*')
        ->name('page.show');
};
