<?php
namespace App\Exceptions\Renderers;

use Throwable;

class JsonErrorRenderer implements ErrorRenderer
{
    public function render(Throwable $exception): void
    {
    }
}