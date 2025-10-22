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
        return view('pages.home')->with( 'hideBreadcrumbs', true);
    }

    public function aboutAction()
    {
        return view('pages.about');
    }

    public function defaultAction()
    {
        throw new AppException(AppError::NOT_FOUND);
    }
}