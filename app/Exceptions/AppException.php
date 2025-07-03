<?php

// AppException.php
namespace Exceptions;

use Exception;

class AppException extends Exception
{
    public function __construct(
        public readonly AppError $error,
        public readonly array $customData = [],
        ?\Throwable $previous = null
    ) {
        parent::__construct($error->message(), $error->status(), $previous);
    }
}
