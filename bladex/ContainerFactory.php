<?php
namespace Bladex;

use DI\Container;

class ContainerFactory
{
    private static ?Container $container = null;

    public static function getContainer(): Container
    {
        if (self::$container === null) {
            $builder = new \DI\ContainerBuilder();
            $builder->useAttributes(true);
            $builder->addDefinitions(useBaseDir('config/di.php'));
            self::$container = $builder->build();
        }

        return self::$container;
    }
}