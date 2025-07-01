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

        $result = $data->getData();

        return useView('home.index')->with(['result' => $result]);
    }
}