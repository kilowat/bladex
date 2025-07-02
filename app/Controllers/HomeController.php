<?php
namespace Controllers;

use Bitrix\Main\HttpRequest;
use Services\DataBaseService;
use DI\Attribute\Inject;
use Requests\NewsGetRequest;
class HomeController extends BaseController
{

    #[Inject]
    protected DataBaseService $data;


    public function indexAction(DataBaseService $data)
    {
        $result = $data->getData();
        $arr = $this->request->getJsonList()->toArray();

        return useView('home.index')->with(['result' => $result]);
    }
}