<?php
namespace Bladex;

use RuntimeException;

class Fixtures
{
    protected static string $path = __DIR__ . '/../fixtures';
    protected static array $cache = [];

    public static function setPath(string $path): void
    {
        self::$path = rtrim($path, '/');
    }

    public static function get(string $name): mixed
    {
        if (isset(self::$cache[$name])) {
            return self::$cache[$name];
        }

        $file = self::$path . '/' . str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $name) . '.php';

        if (!file_exists($file)) {
            throw new RuntimeException("Fixture not found: $name");
        }

        return self::$cache[$name] = include $file;
    }
}