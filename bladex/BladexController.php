<?
namespace Bladex;

use Bitrix\Main\Engine\Controller;

abstract class BladexController extends Controller
{
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

    /**
     * ГЛАВНЫЙ МЕТОД - переопределяем обработку исключений
     */
    protected function runProcessingException(\Exception $e)
    {
        /*
        $exceptionHandling = \Bitrix\Main\Config\Configuration::getValue('exception_handling');
        if (!empty($exceptionHandling['debug'])) {
            $error = new \Bitrix\Main\Error(\Bitrix\Main\Diag\ExceptionHandlerFormatter::format($e));
            $response = useView('errors.debug')->with('error', $error)->getResponse();
        }

        $response->setStatus(500);
        $response->send();

        parent::runProcessingException($e);
        */
    }

}
