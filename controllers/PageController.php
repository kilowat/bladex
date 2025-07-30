<?php
namespace Controllers;
use App\Exceptions\AppError;
use App\Exceptions\AppException;
use App\Services\DataBaseService;
use DI\Attribute\Inject;

class PageController extends BaseController
{
    public function indexAction()
    {
        return useView('home.index');
    }

    public function showAction($page)
    {
        $view = str_replace('-', '_', $page);

        if (useView()->exists($view)) {
            return useView($view);
        }

        throw new AppException(AppError::NOT_FOUND);
    }
}