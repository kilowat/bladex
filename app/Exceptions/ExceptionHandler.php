<?php

namespace App\Exceptions;

use App\Exceptions\Renderers\RendererFactory;
use Throwable;



class ExceptionHandler
{

    public static function handle(Throwable $exception): void
    {
        $renderer = RendererFactory::create();
        $renderer->render($exception);
    }
}
