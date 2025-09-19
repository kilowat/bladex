<?PHP

use Bitrix\Main\Routing\RoutingConfigurator;
include 'bootstrap.php';

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