<?php
namespace App\Controllers;

use Bladex\DIController;

abstract class BaseController extends DIController
{
    protected function getDefaultPreFilters(): array
    {
        return [];
    }
}
