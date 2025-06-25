index.php, 404.php --> include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/routing_index.php');

settings.php

'routing' => ['value' => [
        'config' => ['web.php']
]], 
Это для работы bitrix констолей указываем полный путь до нашего композер файла
'composer' => [
    'value' => [
        'config_path' => 'var/www/myapp/composer.json'
    ]
],

в composer.json нужно указать путь до bitrix composer чтобы не было конфилктов пример:
    "extra": {
        "merge-plugin": {
            "require": [
                "/var/www/bitrix/bitrix/composer-bx.json"
            ]
        }
    }


location ~* ^/local/.*\.php$ {
    deny all;
}

в init.php 
define('APP_PATH', realpath(__DIR__ . '/../../../myapp'));
require_once realpath(APP_PATH.'/bootstrap.php');

подключения роутов

define('APP_PATH', realpath(__DIR__ . '/../../../myapp'));
require_once realpath(APP_PATH.'/bootstrap.php');

use Bitrix\Main\Routing\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $closure = include APP_PATH.'/route/web.php';
    if ($closure instanceof Closure) {
        $closure($routes);
    }
};

симилинк на ассеты

ln -s /var/www/myapp/assets /var/www/public/assets