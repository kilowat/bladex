<?php
namespace App\Exceptions\Renderers;

use Throwable;

class ConsoleErrorRenderer implements ErrorRenderer
{
    public function render(Throwable $exception): void
    {
    }
}