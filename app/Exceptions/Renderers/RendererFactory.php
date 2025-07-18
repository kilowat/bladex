<?php
namespace App\Exceptions\Renderers;

use Throwable;
class RendererFactory
{
    public static function create(): ErrorRenderer
    {
        if (PHP_SAPI === 'cli') {
            return new ConsoleErrorRenderer();
        }

        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';

        if (str_contains($accept, 'application/json') || $xhr === 'XMLHttpRequest') {
            return new JsonErrorRenderer();
        }

        return new HtmlErrorRenderer();
    }
}