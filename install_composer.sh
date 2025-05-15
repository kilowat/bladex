#!/bin/bash

# Скрипт установки Composer для 1C-Bitrix (работает на VM и обычном хостинге)

# Проверяем, установлен ли уже Composer
if ! command -v composer &> /dev/null; then
    echo "Установка Composer..."
    
    # Скачиваем и устанавливаем Composer
    EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

    if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then
        echo >&2 "Ошибка: неверная контрольная сумма Composer!"
        rm composer-setup.php
        exit 1
    fi

    php composer-setup.php --install-dir=/usr/local/bin --filename=composer
    RESULT=$?
    rm composer-setup.php
    
    if [ $RESULT -ne 0 ]; then
        echo "Ошибка установки Composer глобально. Пробуем локальную установку..."
        php composer-setup.php
        mv composer.phar /usr/local/bin/composer
        chmod +x /usr/local/bin/composer
    fi
    
    echo "Composer успешно установлен!"
else
    echo "Composer уже установлен."
fi

# Проверяем версию PHP (Bitrix требует >= 7.1)
PHP_VERSION=$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')
if [[ "$PHP_VERSION" < "7.1" ]]; then
    echo "Внимание: Bitrix требует PHP 7.1 или выше! Текущая версия: $PHP_VERSION"
fi

# Настройка Composer для Bitrix
echo "Настройка Composer для работы с Bitrix..."
composer config -g repo.packagist composer https://api.bitrix24.com/composer/
composer global require bitrix/bitrix

# Создаем базовый composer.json если его нет
if [ ! -f "composer.json" ]; then
    echo "Создаем базовый composer.json..."
    cat > composer.json <<EOF
{
    "name": "bitrix/project",
    "description": "Bitrix Project",
    "type": "project",
    "require": {
        "php": ">=7.1.0",
        "bitrix/bitrix": "^1.0"
    },
    "config": {
        "process-timeout": 1800,
        "vendor-dir": "bitrix/vendor"
    }
}
EOF
fi

echo "Установка завершена! Теперь вы можете использовать:"
echo "  composer install - для установки зависимостей"
echo "  composer require bitrix/module.name - для добавления модулей"