<?php

// AppException.php
namespace App\Exceptions;

use Bitrix\Main\SystemException;
use Exception;

class AppException extends SystemException
{
    public function __construct(
        public readonly AppError $error = AppError::INTERNAL_ERROR,
        public readonly array $customData = [],
        ?\Throwable $previous = null
    ) {
        parent::__construct($error->message(), 0, '', 0, $previous);
    }
}
