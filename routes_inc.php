<?PHP
include 'bootstrap.php';
$routesConfig = useConfig('routes');
use Bitrix\Main\Routing\RoutingConfigurator;

return function (RoutingConfigurator $routes) use ($routesConfig) {
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