<?php

require_once __DIR__ . '/vendor/autoload.php';

// Подключаем роутинг к Bitrix
\Bitrix\Main\Loader::includeModule('main');

// Регистрируем наш роутинг
Bitrix\Main\Routing\RouterConfigurator::setDefaultConfigurationFile($_SERVER['DOCUMENT_ROOT'] . '/app/Routes/routes.php');