<?php
namespace App\Exceptions\Reports;

use Throwable;
use App\Exceptions\Reports\ErrorReport;
use App\Exceptions\AppException;
class JsonErrorReport implements ErrorReport
{
    public function render(AppException $exception): void
    {
    }
}