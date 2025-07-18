<?php
namespace App\Exceptions\Renderers;

use Throwable;

interface ErrorRenderer
{
    public function render(Throwable $exception): void;
}