<?php
namespace App\Controllers;

use App\Services\DataBaseService;
use DI\Attribute\Inject;

class HomeController extends BaseController
{
    #[Inject]
    protected DataBaseService $data;


    public function indexAction()
    {
        $res = $this->data->getData();

        return useView('home.index', []);
    }
}