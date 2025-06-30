<?PHP
include 'bootstrap.php';
$routesConfig = useConfig('routes');
use Bitrix\Main\Routing\RoutingConfigurator;

return function (RoutingConfigurator $routes) use ($routesConfig) {

    foreach ($routesConfig as $route) {
        $closure = include "routes/{$route}.php";
        if ($closure instanceof Closure) {
            $closure($routes);
        }
    }
};