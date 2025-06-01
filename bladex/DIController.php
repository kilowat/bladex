<?php
namespace Bladex;

use Bitrix\Main\Engine\Controller;
use Bladex\Inject;
use DI\Container;
use ReflectionClass;
use RuntimeException;

abstract class DIController extends Controller
{
    protected static Container $container;
    private static array $injectablePropertiesCache = [];

    public static function setContainer(Container $container): void
    {
        static::$container = $container;
    }

    public function __construct($request = null)
    {
        parent::__construct($request);
        $this->injectProperties();
    }

    protected function injectProperties(): void
    {
        $className = static::class;

        if (!isset(self::$injectablePropertiesCache[$className])) {
            self::$injectablePropertiesCache[$className] = $this->collectInjectableProperties($className);
        }

        foreach (self::$injectablePropertiesCache[$className] as $property) {
            $type = $property->getType();
            if (!$type || $type->isBuiltin()) {
                throw new RuntimeException("Property '{$property->getName()}' must have a non-builtin type for DI injection.");
            }

            $dependency = static::$container->get($type->getName());

            $property->setAccessible(true);
            $property->setValue($this, $dependency);
        }
    }

    /**
     * Рекурсивно собирает все свойства с #[Inject], включая родительские
     */
    private function collectInjectableProperties(string $className): array
    {
        $injectable = [];
        $reflection = new ReflectionClass($className);

        do {
            foreach ($reflection->getProperties() as $property) {
                $attrs = $property->getAttributes(Inject::class);
                if (!empty($attrs)) {
                    $injectable[] = $property;
                }
            }

            $reflection = $reflection->getParentClass();
        } while ($reflection);

        return $injectable;
    }

    public function runAction($actionName, array $parameters = [])
    {
        $method = $actionName . 'Action';

        if (!method_exists($this, $method)) {
            throw new RuntimeException("Метод действия {$method} не найден в " . static::class);
        }

        return static::$container->call([$this, $method], $parameters);
    }
}
