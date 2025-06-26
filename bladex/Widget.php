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

        // Фильтруем конфигурацию, оставляя только разрешенные ключи
        $instance->config = $instance->filterConfig($config);

        return $instance;
    }

    /**
     * Фильтрует переданную конфигурацию, оставляя только ключи,
     * которые определены в свойстве $config наследуемого класса
     */
    protected function filterConfig(array $inputConfig): array
    {
        $allowedKeys = array_keys($this->config);

        $filteredConfig = array_intersect_key($inputConfig, array_flip($allowedKeys));

        return array_merge($this->config, $filteredConfig);
    }

    abstract public function run(): HttpResponse;

    public function render(): string
    {
        $response = $this->run();
        return method_exists($response, 'getContent') ? $response->getContent() : '';
    }
}