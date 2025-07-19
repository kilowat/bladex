<?php
namespace App\Exceptions\Reports;

use App\Exceptions\Reports\ErrorReport;

class ErrorReportFactory
{
    public static function create(): ErrorReport
    {
        if (PHP_SAPI === 'cli') {
            return new ConsoleErrorReport();
        }

        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';

        if (str_contains($accept, 'application/json') || $xhr === 'XMLHttpRequest') {
            return new JsonErrorReport();
        }

        return new HtmlErrorReport();
    }
}