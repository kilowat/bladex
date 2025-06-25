index.php, 404.php --> include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/routing_index.php');

settings.php

'routing' => ['value' => [
        'config' => ['web.php']
]], 

'composer' => [
    'value' => [
        'config_path' => $_SERVER['DOCUMENT_ROOT'] . '/local/composer.json'
    ]
],


location ~* ^/local/.*\.php$ {
    deny all;
}

в init.php 
define('APP_PATH', realpath(__DIR__ . '/../../../myapp'));
require_once realpath(APP_PATH.'/bootstrap.php');

подключения роутов
use Bitrix\Main\Routing\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $closure = include APP_PATH.'/route/web.php';
    if ($closure instanceof Closure) {
        $closure($routes);
    }
};

симилинк на ассеты

ln -s /var/www/myapp/assets /var/www/public/assets