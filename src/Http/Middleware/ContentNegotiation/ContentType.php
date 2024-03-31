<?php

declare(strict_types=1);

namespace App\Http\Middleware\ContentNegotiation;

enum ContentType: string
{
    case JSON = 'application/json';
    case JSON_PROBLEM = 'application/problem+json';
    case HTML = 'text/html';
    case XML = 'application/xml';
    case CSV = 'text/csv';

    public function format(): string
    {
        return match ($this) {
            self::JSON, self::JSON_PROBLEM => 'json',
            self::HTML => 'html',
            self::XML => 'xml',
            self::CSV => 'csv',
        };
    }
}
