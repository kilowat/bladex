<?php
namespace Bladex;

use Bitrix\Main\HttpResponse;

abstract class Widget
{
    protected array $config = [];

    public function __construct()
    {
        // Внедрение зависимостей через контейнер
    }

    public static function make(array $config = []): static
    {
        $container = ContainerFactory::getContainer();

        /** @var static $instance */
        $instance = $container->make(static::class);
        $instance->config = $config;

        return $instance;
    }

    abstract public function run(): HttpResponse;

    public function render(): string
    {
        $response = $this->run();
        return method_exists($response, 'getContent') ? $response->getContent() : '';
    }
}
