<?
namespace Bladex;

use Bitrix\Main\Engine\Controller;

abstract class DIController extends Controller
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
}
