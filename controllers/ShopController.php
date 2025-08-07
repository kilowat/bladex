<?php
namespace Controllers;

use Bitrix\Main\Page\Frame;
use Bitrix\Main\Web\Response\HtmlResponse;
use Bitrix\Main\Context;
use Bitrix\Main\Composite\Engine;


class ShopController extends BaseController
{
    public function indexAction()
    {
        return useView('pages.shop.index');
    }
}