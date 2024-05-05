<?php

declare(strict_types=1);

namespace App\Pagination;

readonly class PaginationMetadata
{
    public const ITEMS_PER_PAGE = 10;

    public function __construct(
        public int $page,
        public int $itemsPerPage,
        public array $links,
        public array $meta,
    ) {
    }
}
