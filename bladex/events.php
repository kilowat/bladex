<?php

use Bitrix\Main\Application;
use Bitrix\Main\EventManager;


EventManager::getInstance()->addEventHandler('main', 'OnProlog', function () {
    global $USER;
    $isDoClearCacahe = Application::getInstance()->getContext()->getRequest()->get('blade_cache_clear') == 'Y';
    if ($isDoClearCacahe) {
        if ($USER->IsAuthorized() && $USER->IsAdmin()) {
            \Bladex\BladeRenderer::getInstance()->clearCache();
        }
    }
});
