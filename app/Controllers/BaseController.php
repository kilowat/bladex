<?php
namespace App\Controllers;

use Bitrix\Main\Engine\Controller;
abstract class BaseController extends Controller
{


    public function __construct($request = null)
    {
        parent::__construct($request);


    }
    protected function getDefaultPreFilters(): array
    {
        return [];
    }

}
