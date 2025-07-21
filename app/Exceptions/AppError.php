<?php

namespace App\Exceptions;

enum AppError: string
{
    case NOT_FOUND = '404';
    case FORBIDDEN = 'forbidden';
    case VALIDATION_FAILED = 'validation_failed';
    case SERVER_ERROR = 'server';

    public function status(): int
    {
        return match ($this) {
            self::NOT_FOUND => 404,
            self::FORBIDDEN => 403,
            self::VALIDATION_FAILED => 422,
            self::SERVER_ERROR => 500,
        };
    }

    public function message(): string
    {
        return match ($this) {
            self::NOT_FOUND => 'Resource not found.',
            self::FORBIDDEN => 'Access denied.',
            self::VALIDATION_FAILED => 'Validation failed.',
            self::SERVER_ERROR => 'Internal server error.',
        };
    }
    public function view(): string
    {
        return match ($this) {
            self::NOT_FOUND => 'errors.404',
            default => 'errors.default',
        };
    }

    public function shouldToLog()
    {
        return match ($this) {
            default => false,
        };
    }
}
