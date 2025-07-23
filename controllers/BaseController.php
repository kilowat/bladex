<?php
namespace Controllers;

use Bladex\BladexController;

abstract class BaseController extends BladexController
{
    protected function getDefaultPreFilters(): array
    {
        return [];
    }

}
