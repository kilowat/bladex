<?php

namespace Bladex;

use Bladex\ContainerFactory;
use Bitrix\Main\Engine\Controller;
use DI\Container;
use ReflectionClass;
use ReflectionProperty;
use RuntimeException;
/*
 *  // Внедрение по name
    #[Inject(name: 'database.main')] или просто   
     #[Inject]
    protected LoggerService $defaultLogger;
 */
abstract class DIController extends Controller
{
    private static array $injectablePropertiesCache = [];
    protected Container $container;

    public function __construct($request = null)
    {
        parent::__construct($request);
        $this->container = ContainerFactory::getContainer();
        $this->injectProperties();
    }

    protected function injectProperties(): void
    {
        $className = static::class;

        if (!isset(self::$injectablePropertiesCache[$className])) {
            self::$injectablePropertiesCache[$className] = $this->collectInjectableProperties($className);
        }

        foreach (self::$injectablePropertiesCache[$className] as $propertyInfo) {
            $this->injectProperty($propertyInfo);
        }
    }

    private function injectProperty(array $propertyInfo): void
    {
        /** @var ReflectionProperty $property */
        $property = $propertyInfo['property'];
        /** @var Inject $injectAttribute */
        $injectAttribute = $propertyInfo['inject_attribute'];

        $dependency = null;

        // Проверяем name
        if ($injectAttribute->name !== null) {
            $dependency = $this->container->get($injectAttribute->name);
        }
        // Проверяем alias
        elseif ($injectAttribute->alias !== null) {
            $dependency = $this->container->get($injectAttribute->alias);
        }
        // Стандартное внедрение по типу
        else {
            $type = $property->getType();
            if (!$type || $type->isBuiltin()) {
                throw new RuntimeException(
                    "Property '{$property->getName()}' must have a non-builtin type for DI injection " .
                    "or specify 'name' or 'alias' parameter in #[Inject] attribute."
                );
            }
            $dependency = $this->container->get($type->getName());
        }

        if ($dependency === null) {
            throw new RuntimeException("Unable to resolve dependency for property '{$property->getName()}'");
        }

        $property->setAccessible(true);
        $property->setValue($this, $dependency);
    }

    private function collectInjectableProperties(string $className): array
    {
        $injectable = [];
        $reflection = new ReflectionClass($className);

        do {
            foreach ($reflection->getProperties() as $property) {
                $injectAttrs = $property->getAttributes(Inject::class);

                if (!empty($injectAttrs)) {
                    // Берем первый атрибут Inject (должен быть только один)
                    $injectAttribute = $injectAttrs[0]->newInstance();

                    $injectable[] = [
                        'property' => $property,
                        'inject_attribute' => $injectAttribute
                    ];
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

        return $this->container->call([$this, $method], $parameters);
    }
}