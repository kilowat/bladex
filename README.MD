index.php --> include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/routing_index.php');

### settings.php
'routing' => ['value' => [
        'config' => ['web.php']
]], 

#### Решения конфилкта bitrix/composer.json и myapp/composer.json
Это для работы bitrix консолей указываем полный путь до нашего композер файла.
##### settings.php
'composer' => [
    'value' => [
        'config_path' => 'var/www/myapp/composer.json'
    ]
],

### myapp/composer.json 
нужно указать путь до bitrix composer чтобы не было конфилктов пример:
    "extra": {
        "merge-plugin": {
            "require": [
                "/var/www/bitrix/bitrix/composer-bx.json"
            ]
        }
    }

### init.php 
require_once realpath('/var/www/myapp/bootstrap.php');

### bitrix/routes/web.php
return include '/var/www/myapp/routes_inc.php';

### симилинк на ассеты
Пробросить assets через ссылку в публичную директорию где расположен битрикс.
ln -s /var/www/myapp/assets /var/www/bitrix/assets

## Для Bitrixdoc
пробросить volumes в php и nginx ./www/myapp:/var/www/myapp
### 404.php --> include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/routing_index.php');

