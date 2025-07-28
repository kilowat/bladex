<?php
namespace Controllers;
use App\Exceptions\AppError;
use App\Exceptions\AppException;
use App\Services\DataBaseService;
use DI\Attribute\Inject;

class HomeController extends BaseController
{
    //Можно через аттрибут
    #[Inject]
    protected DataBaseService $dataService;

    // Можно через параметр
    public function indexAction(DataBaseService $dataService)
    {
        $siteName = $dataService->getSiteName();

        return useView('pages.home')->with('siteName', $siteName);
    }
}