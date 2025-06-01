<?php
namespace App\Controllers;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Request;
use DI\Container;

abstract class BaseController extends Controller
{

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }

    protected function getDefaultPreFilters(): array
    {
        return [];
    }
    protected static Container $container;

    public static function setContainer(Container $container): void
    {
        static::$container = $container;
    }

    public function resolve(string $class)
    {
        return static::$container->get($class);
    }

    public function call($callable)
    {
        return static::$container->call($callable);
    }

    public function runAction($actionName, array $parameters = [])
    {
        $method = $actionName . 'Action';

        if (!method_exists($this, $method)) {
            throw new \RuntimeException("Метод действия {$method} не найден в контроллере " . static::class);
        }

        return static::$container->call([$this, $method], $parameters);
    }
}
