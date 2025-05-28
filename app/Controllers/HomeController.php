<?php
namespace App\Controllers;
use Bitrix\Main\Engine\Controller;
use Bladex\BladeRenderer;
class HomeController extends BaseController
{
    public function indexAction()
    {
        return $this->render('home.index', [
            'siteName' => 'test',
            'news' => [],
        ]);
    }
}