<?php
namespace App\Controllers;

use Bitrix\Main\Engine\Controller;

class HomeController extends Controller {

    public function indexAction()
    {
        return 'hello';
    }
}