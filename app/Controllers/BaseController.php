<?php
namespace Controllers;

use Bladex\BladexController;

abstract class BaseController extends BladexController
{
    protected function getDefaultPreFilters(): array
    {
        return [];
    }

    protected function runProcessingThrowable(\Throwable $throwable)
    {
        //Здесь можно добавить обработку своих ошибок
        parent::runProcessingThrowable($throwable);
    }
}
