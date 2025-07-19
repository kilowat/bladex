<?php
namespace App\Exceptions\Reports;

use App\Exceptions\AppError;
use App\Exceptions\AppException;
use Throwable;

interface ErrorReport
{
    public function render(AppException $exception): void;
}