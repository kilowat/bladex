<?
namespace Bladex;

use App\Exceptions\AppError;
use Bitrix\Main\Application;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Error;
use App\Exceptions\AppException;
use App\Exceptions\AppExceptionHandler;
use App\Exceptions\ExceptionHandler;
use Exception;

abstract class BladexController extends Controller
{
    public $debugView = 'errors.debug';

    public function init()
    {
        parent::init();
        $container = \Bladex\ContainerFactory::getContainer();
        $container->injectOn($this);
    }

    protected function processAfterAction(\Bitrix\Main\Engine\Action $action, $result)
    {
        if ($result instanceof View) {

            return $result->getResponse();
        }
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

    public function finalizeResponse(\Bitrix\Main\Response $response)
    {
        if (!empty($this->getErrors())) {
            $errors = $this->getErrors();

            $firstError = $errors[0];
            $appError = AppError::tryFrom($firstError->getCode());
            $exceptionHandling = \Bitrix\Main\Config\Configuration::getValue('exception_handling');
            $viewError = !empty($exceptionHandling['debug']) && $appError == null ? $this->debugView : $appError->view();
            $finalResponse = !$this->request->isJson() ? useView($viewError)->with('errors', $errors)->getResponse() : $response;
            $finalResponse->setStatus($finalResponse->getStatus() == 0 ? $appError?->status() ?? 500 : $finalResponse->getStatus());
            $finalResponse->send();
        }
    }
}
