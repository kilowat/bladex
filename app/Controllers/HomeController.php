<?php
namespace App\Controllers;

use App\Services\DataBaseService;
class HomeController extends BaseController
{

    public function indexAction(DataBaseService $service)
    {
        return view('home.index', []);
    }
}