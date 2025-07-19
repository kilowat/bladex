<?php

namespace App\Exceptions;

use App\Exceptions\Reports\ErrorReportFactory;
use Throwable;



class ExceptionHandler
{

    public static function handle(Throwable $exception): void
    {
        $appException = $exception instanceof AppException ? $exception : self::getAppException($exception);
        $report = ErrorReportFactory::create();
        $report->render($appException);
    }


    private static function getAppException(Throwable $exception)
    {
        return new AppException(AppError::INTERNAL_ERROR, [], $exception);
    }
}
