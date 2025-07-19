<?php

// AppException.php
namespace App\Exceptions;

use Exception;

class AppException extends Exception
{
    public function __construct(
        public readonly AppError $error = AppError::INTERNAL_ERROR,
        public readonly array $customData = [],
        ?\Throwable $previous = null
    ) {
        parent::__construct($error->message(), $error->value, $previous, );
    }
}
