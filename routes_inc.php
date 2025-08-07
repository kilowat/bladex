<?PHP
include 'bootstrap.php';

use Bitrix\Main\Routing\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routesConfig = useConfig('routes');
    foreach ($routesConfig as $route) {
        $routeFile = useBaseDir($route);
        if (!file_exists($routeFile)) {
            continue;
        }

        $closure = include $routeFile;
        if ($closure instanceof Closure) {
            $closure($routes);
        }
    }
};

/**
 * other variant 
 * 
 * <?PHP

use Bitrix\Main\Routing\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routesConfig = include('config/routes.php');

    foreach ($routesConfig as $route) {
        $routeFile = dirname(__FILE__) . '/' . $route;
        if (!file_exists($routeFile)) {
            continue;
        }

        $closure = include $routeFile;
        if ($closure instanceof Closure) {
            $closure($routes);
        }
    }
};
 */