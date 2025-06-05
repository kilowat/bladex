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


