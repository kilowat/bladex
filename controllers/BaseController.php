<?php
namespace Controllers;
use App\Exceptions\AppException;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Error;

abstract class BaseController extends Controller
{
    public function init()
    {
        parent::init();
        
        $container = \Common\ContainerFactory::getContainer();

        $container->injectOn($this);
         
    }

    protected function runProcessingThrowable(\Throwable $throwable)
    {
        if ($throwable instanceof AppException) {
            $this->addError(
                new Error(
                    $throwable->error->message(),
                    $throwable->error->value,
                    $throwable->customData
                )
            );

        } else {
            parent::runProcessingThrowable($throwable);
        }

    }


    protected function writeToLogException(\Throwable $e)
    {
        if ($e instanceof AppException && !$e->error->shouldToLog()) {
            return;
        }
        parent::writeToLogException($e);
    }

    protected function getDefaultPreFilters(): array
    {
        return [
 
        ];
    }

}
