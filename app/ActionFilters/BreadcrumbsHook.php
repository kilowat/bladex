<?php
namespace App\ActionFilters;
use Bitrix\Main\Context;
use Bitrix\Main\Event;
use \Bladex\Breadcrumbs;

class BreadcrumbsHook extends \Bitrix\Main\Engine\ActionFilter\Base
{
    public function onBeforeAction(Event $event)
    {
        Breadcrumbs::for('home', function ($trail) {
            $trail->push('Главная', '/');
        });

        Breadcrumbs::for('shop.index', function ($trail) {
            $trail->parent('home');
            $trail->push('Shop', '/shop');
        });
    }
}