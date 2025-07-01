<?php
namespace Controllers;

use Services\DataBaseService;
use DI\Attribute\Inject;

class HomeController extends BaseController
{

    #[Inject]
    protected DataBaseService $data;


    public function indexAction(DataBaseService $data)
    {

        $res = $data->getData();

        return useView()->response('home.index', []);
    }
}