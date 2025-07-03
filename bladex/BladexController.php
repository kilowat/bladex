<?
namespace Bladex;

use Bitrix\Main\Engine\Controller;
use Exceptions\AppException;
use Exceptions\AppExceptionHandler;

abstract class BladexController extends Controller
{
    public $debugView = 'errors.debug';
    public $defaultErrorView = 'errors.default';

    public function __construct($request = null)
    {
        parent::__construct($request);

        // Получаем контейнер
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
        if ($result instanceof View) {

            return $result->getResponse();
        }
    }
    /*
    protected function runProcessingThrowable(\Throwable $throwable)
    {
        if ($throwable instanceof AppException) {
            $handler = new AppExceptionHandler($this);
            $handler->runProcessingThrowable($throwable);
        } else {
            parent::runProcessingThrowable($throwable);
        }
    }
    */
    public function finalizeResponse(\Bitrix\Main\Response $response)
    {
        if (!$this->request->isJson() && !$this->request->isAjaxRequest() && !empty($this->getErrors())) {
            $errors = $this->getErrors();
            $exceptionHandling = \Bitrix\Main\Config\Configuration::getValue('exception_handling');
            $viewError = !empty($exceptionHandling['debug']) ? $this->debugView : $this->defaultErrorView;
            $response = useView($viewError)->with('errors', $errors)->getResponse();
            $response->setStatus(500);
            $response->send();
        }
    }
}
