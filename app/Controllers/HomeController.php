<?php
namespace App\Controllers;
class HomeController extends BaseController
{
    public function indexAction()
    {
        return view('home.index', []);
    }
}