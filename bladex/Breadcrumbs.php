<?php
namespace Bladex;

class Breadcrumbs
{
    protected static array $routes = [];
    protected static ?string $currentRouteName = null;
    protected static array $currentParams = [];

    public static function for(string $name, callable $callback): void
    {
        static::$routes[$name] = $callback;
    }

    public static function generate(string $name, ...$params): self
    {
        $instance = new self();

        if (!isset(static::$routes[$name])) {
            throw new \RuntimeException("Breadcrumb route [{$name}] not registered.");
        }

        $callback = static::$routes[$name];

        // Call closure with $instance and params
        $callback($instance, ...$params);

        return $instance;
    }

    public static function setCurrentRoute(string $name, ...$params): void
    {
        static::$currentRouteName = $name;
        static::$currentParams = $params;
    }

    public static function current(): self
    {
        if (!static::$currentRouteName) {
            throw new \RuntimeException('Breadcrumbs current route is not set.');
        }

        return static::generate(static::$currentRouteName, ...static::$currentParams);
    }

    protected array $trail = [];

    public function push(string $title, ?string $url = null): self
    {
        $this->trail[] = ['title' => $title, 'url' => $url];
        return $this;
    }

    public function parent(string $name, ...$params): self
    {
        if (!isset(static::$routes[$name])) {
            throw new \RuntimeException("Breadcrumb parent [{$name}] not registered.");
        }

        $parent = static::generate($name, ...$params);
        $this->trail = array_merge($this->trail, $parent->get());

        return $this;
    }

    public function get(): array
    {
        return $this->trail;
    }
}