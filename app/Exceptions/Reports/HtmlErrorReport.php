<?php
namespace App\Exceptions\Reports;
use App\Exceptions\AppError;
use App\Exceptions\AppException;
use App\Exceptions\Reports\ErrorReport;

use Throwable;

class HtmlErrorReport implements ErrorReport
{
    protected $view = 'errors.default';
    public function render(AppException $exception): void
    {
        $response = match ($exception->error) {
            AppError::INTERNAL_ERROR => useView('errors.default')
                ->with(['exception' => $exception])
                ->getResponse()
                ->setStatus($exception->error->status())
                ->send(),
        };
    }
}