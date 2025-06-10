<?php

use Bitrix\Main\EventManager;
use Bladex\BladeRenderer;

EventManager::getInstance()->addEventHandler(
    'main',
    'OnBeforeClearCache',
    function () {
        BladeRenderer::getInstance()->clearCache();
    }
);