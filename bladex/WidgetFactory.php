<?php
namespace Bladex;

class WidgetFactory
{
    public static function fromView(string $widgetName, array|string $configOrPreset = [], array $overrides = []): string
    {
        $map = include $_SERVER['DOCUMENT_ROOT'] . '/local/config/widgets.php';

        if (!isset($map[$widgetName])) {
            return $_ENV['APP_DEBUG'] ? "Widget \"$widgetName\" not defined in config." : '';
        }

        $widgetDef = $map[$widgetName];
        $class = $widgetDef['class'] ?? null;

        if (!$class || !class_exists($class) || !is_subclass_of($class, Widget::class)) {
            return $_ENV['APP_DEBUG'] ? "Invalid widget class for \"$widgetName\"." : '';
        }

        $presetConfig = [];

        if (is_string($configOrPreset)) {
            $presetConfig = $widgetDef['configs'][$configOrPreset] ?? [];
        } elseif (is_array($configOrPreset)) {
            $presetConfig = $configOrPreset;
        }

        $config = array_merge($presetConfig, $overrides);

        /** @var Widget $widget */
        $widget = $class::make($config);

        try {
            return $widget->render();
        } catch (\Throwable $e) {
            return $_ENV['APP_DEBUG'] ? "Widget \"$widgetName\" error: " . $e->getMessage() : '';
        }
    }
}
