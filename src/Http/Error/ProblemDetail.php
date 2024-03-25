<?php

declare(strict_types=1);

namespace App\Http\Error;

enum ProblemDetail: int
{
    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case METHOD_NOT_ALLOWED = 405;
    case INTERNAL_SERVER_ERROR = 500;
    case NOT_IMPLEMENTED = 501;

    public function type(): string
    {
        return match ($this) {
            self::BAD_REQUEST => 'https://datatracker.ietf.org/doc/html/rfc7231#section-6.5.1',
            self::UNAUTHORIZED => 'https://datatracker.ietf.org/doc/html/rfc7235#section-3.1',
            self::FORBIDDEN => 'https://datatracker.ietf.org/doc/html/rfc7231#section-6.5.3',
            self::NOT_FOUND => 'https://datatracker.ietf.org/doc/html/rfc7231#section-6.5.4',
            self::METHOD_NOT_ALLOWED => 'https://datatracker.ietf.org/doc/html/rfc7231#section-6.5.5',
            self::NOT_IMPLEMENTED => 'https://datatracker.ietf.org/doc/html/rfc7231#section-6.6.2',
            self::INTERNAL_SERVER_ERROR => 'https://datatracker.ietf.org/doc/html/rfc7231#section-6.6.1'
        };
    }
}
