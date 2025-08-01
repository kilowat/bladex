<?php
namespace Widgets\Breadcrumbs;


class Builder
{
    protected array $routes = [];

    public function for(string $name, callable $callback): void
    {
        $this->routes[$name] = $callback;
    }

    public function generate(string $name, ...$params)
    {
        $generator = new Generator();
        if (isset($this->routes[$name])) {
            call_user_func_array($this->routes[$name], array_merge([$generator], $params));
        }
        return $generator;
    }
}