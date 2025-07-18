<?php
namespace App\Exceptions\Renderers;

use Throwable;

class HtmlErrorRenderer implements ErrorRenderer
{
    public function render(Throwable $exception): void
    {
    }
}