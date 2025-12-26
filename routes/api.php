<?php


use Bitrix\Main\Routing\RoutingConfigurator;

return function (RoutingConfigurator $routes) {

    $routes->get('/test', [Controllers\PageController::class, 'test'])
        ->name('test');

    $routes->get('{path}', [Controllers\PageController::class, 'default'])
        ->name('default')
        ->where('path', '.*');
    ;
    
};
