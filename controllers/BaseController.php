<?php
namespace Controllers;

use App\ActionFilters\BreadcrumbsHook;
use Bladex\BladexController;

abstract class BaseController extends BladexController
{
    protected function getDefaultPreFilters(): array
    {
        return [
            new BreadcrumbsHook(),
        ];
    }

}
