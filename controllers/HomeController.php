<?php
namespace Controllers;

use App\Exceptions\AppError;
use App\Exceptions\AppException;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Error;
use Bitrix\Main\SystemException;
use Exception;
use App\Services\DataBaseService;
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
        throw new AppException(AppError::INTERNAL_ERROR);
        throw new SystemException('test', 400);
        //$this->addError(new Error('test'));
        //$this->runProcessingIfUserNotAuthorized();

        return useView('home.index')->with(['result' => $result]);
    }
}