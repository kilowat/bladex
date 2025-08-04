
## Установка

### в www/public
index.php --> include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/routing_index.php');

### bitrix/settings.php
'routing' => ['value' => [
        'config' => ['web.php']
]], 

### Скачать фрейморвк в директорию во вне public и переименовать его например myapp

### myapp/composer.json 
нужно указать путь до bitrix composer чтобы не было конфилктов пример:
    "extra": {
        "merge-plugin": {
            "require": [
                "/var/www/bitrix/bitrix/composer-bx.json" <---- полный путь до bitrix
            ]
        }
    }

#### Решения конфилкта bitrix/composer.json
Это для работы bitrix консолей если нужно использовать указываем полный путь до нашего композер файла.
##### bitrix/settings.php
'composer' => [
    'value' => [
        'config_path' => 'var/www/myapp/composer.json'
    ]
],

### init.php 
require_once realpath('/var/www/myapp/bootstrap.php');

### bitrix/routes/web.php
return include '/var/www/myapp/routes_inc.php';

### симилинк на ассеты
Пробросить assets через ссылку в публичную директорию где расположен битрикс.
ln -s /var/www/myapp/assets /var/www/bitrix/public/assets

## Для Bitrixdoc
Простое решение чтобы из коробки завелось в bitrixdoc 
Пробросить volumes в docker-compose.yml в php и nginx ./www/myapp:/var/www/myapp
B public файлах bitrix в 404.php прописать.
404.php --> include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/routing_index.php');

