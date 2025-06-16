<?php
namespace Bladex;

class WidgetFactory
{
    public static function create(string $name, array $config = []): Widget
    {
        // Пример: 'news.latest' => '\\Widgets\\News\\Latest'
        $parts = explode('.', $name);
        $parts = array_map('ucfirst', $parts);
        $class = 'App\\Widgets\\' . implode('\\', $parts);

        if (!class_exists($class) || !is_subclass_of($class, Widget::class)) {
            throw new \RuntimeException("Widget class {$class} not found or not a subclass of Widget.");
        }

        return $class::make($config);
    }
}
