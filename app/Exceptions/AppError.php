<?php

namespace App\Exceptions;

enum AppError: int
{
    case NOT_FOUND = 3;
    case FORBIDDEN = 2;
    case VALIDATION_FAILED = 1;
    case INTERNAL_ERROR = 0;

    public function status(): int
    {
        return match ($this) {
            self::NOT_FOUND => 404,
            self::FORBIDDEN => 403,
            self::VALIDATION_FAILED => 422,
            self::INTERNAL_ERROR => 500,
        };
    }

    public function message(): string
    {
        return match ($this) {
            self::NOT_FOUND => 'Resource not found.',
            self::FORBIDDEN => 'Access denied.',
            self::VALIDATION_FAILED => 'Validation failed.',
            self::INTERNAL_ERROR => 'Internal server error.',
        };
    }
}
