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

в init.php 

require_once realpath('/var/www/myapp/bootstrap.php');

подключения роутов
return include '/var/www/myapp/routes_inc.php';

##Для Bitrixdoc
пробросить volumes в php и nginx ./www/myapp:/var/www/myapp

###симилинк на ассеты
ln -s /var/www/myapp/assets /var/www/bitrix/assets