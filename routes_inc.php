<?PHP

use Bitrix\Main\Routing\RoutingConfigurator;
include 'bootstrap.php';

return function (RoutingConfigurator $routes) {
    $routesConfig = config('routes');
    foreach ($routesConfig as $route) {
        $routeFile = baseDir($route);
        if (!file_exists($routeFile)) {
            continue;
        }

        $closure = include $routeFile;
        if ($closure instanceof Closure) {
            $closure($routes);
        }
    }
};