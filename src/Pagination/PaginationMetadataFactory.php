<?php

declare(strict_types=1);

namespace App\Pagination;

use Psr\Http\Message\ServerRequestInterface as Request;

class PaginationMetadataFactory
{
    public static function create(Request $request, int $totalItems): PaginationMetadata
    {
        $queryParams = $request->getQueryParams();
        $page = (int) ($queryParams['page'] ?? 1);
        $itemsPerPage = (int) ($queryParams['itemsPerPage'] ?? PaginationMetadata::ITEMS_PER_PAGE);

        $totalPages = (int) ceil($totalItems / $itemsPerPage);
        $path = $request->getUri()->getPath();

        $links = [
            'self' => "$path?page=$page&itemsPerPage=$itemsPerPage",
            'first' => "$path?page=1&itemsPerPage=$itemsPerPage",
            'last' => "$path?page=$totalPages&itemsPerPage=$itemsPerPage",
            'prev' => $page > 1 ? "$path?page=" . ($page - 1) . "&itemsPerPage=$itemsPerPage" : null,
            'next' => $page < $totalPages ? "$path?page=" . ($page + 1) . "&itemsPerPage=$itemsPerPage" : null
        ];

        $meta = [
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'itemsPerPage' => $itemsPerPage
        ];

        return new PaginationMetadata($page, $itemsPerPage, $links, $meta);
    }
}