<?php
namespace App\ActionFilters;
use Bitrix\Main\Event;
use Bitrix\Main\Application;
class BladeClearCacheHook extends \Bitrix\Main\Engine\ActionFilter\Base
{
    public function onBeforeAction(Event $event)
    {
        global $USER;
        $isDoClearCacahe = Application::getInstance()->getContext()->getRequest()->get('blade_cache_clear') == 'Y';
        if ($isDoClearCacahe) {

            if ($USER->IsAuthorized() && $USER->IsAdmin()) {
                \Bladex\BladeRenderer::getInstance()->clearCache();
            }
        }
    }
}