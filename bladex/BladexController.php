<?
namespace Bladex;

use Bitrix\Main\Application;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Error;
use App\Exceptions\AppException;
use App\Exceptions\AppExceptionHandler;
use App\Exceptions\ExceptionHandler;

abstract class BladexController extends Controller
{
    public $debugView = 'errors.debug';
    public $defaultErrorView = 'errors.default';

    private $errorHandler = null;

    public function init()
    {
        parent::init();
        $container = \Bladex\ContainerFactory::getContainer();
        $container->injectOn($this);
    }

    public function runAction($actionName, array $parameters = [])
    {
        $method = $actionName . 'Action';

        if (!method_exists($this, $method)) {
            throw new \RuntimeException("Метод действия {$method} не найден в " . static::class);
        }

        return ContainerFactory::getContainer()->call([$this, $method], $parameters);

    }

    protected function processAfterAction(\Bitrix\Main\Engine\Action $action, $result)
    {
        var_dump(Application::getInstance()->getContext()->getResponse());
        die();
        if ($result instanceof View) {

            return $result->getResponse();
        }
    }

    protected function runProcessingThrowable(\Throwable $throwable)
    {
        parent::runProcessingThrowable($throwable);
    }


    public function finalizeResponse(\Bitrix\Main\Response $response)
    {
        /*
        //for default error
        if (!$this->request->isJson() && !$this->request->isAjaxRequest() && !empty($this->getErrors())) {
            $errors = $this->getErrors();
            $exceptionHandling = \Bitrix\Main\Config\Configuration::getValue('exception_handling');
            $viewError = !empty($exceptionHandling['debug']) ? $this->debugView : $this->defaultErrorView;
            $response = useView($viewError)->with('errors', $errors)->getResponse();
            $response->setStatus(500);
            $response->send();
        }
        */
    }

}
