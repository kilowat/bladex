<?php

namespace App\Exceptions;

enum AppError: string
{
    case NOT_FOUND = 'not_found';
    case FORBIDDEN = 'forbidden';
    case VALIDATION_FAILED = 'validation_failed';
    case INTERNAL_ERROR = 'internal_error';

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
