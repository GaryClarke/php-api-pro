<?php

declare(strict_types=1);

namespace App\Repository\QueryUtils;

use Doctrine\ORM\QueryBuilder;

class Sort
{
    public static function apply(
        ?string $sort,
        QueryBuilder $qb,
        string $tableName,
        array $supportedFields
    ): void {
        // Return if no sort filter
        if (!$sort) {
            return;
        }

        // Split sort fields
        $sortFields = explode(',', $sort);

        // Loop over sort fields
        foreach ($sortFields as $sortField) {
            // Set default order to asc
            $sortOrder = 'ASC';
            // Check if begins with -
            if ($sortField[0] === '-') {
                // Set to desc if so
                $sortOrder = 'DESC';
                // Remove the - from the name (correct name needed for query)
                $sortField = substr($sortField, 1);
            }
            // Check if field supports sorting
            if (in_array($sortField, $supportedFields)) {
                // Add orderBy if so
                $qb->addOrderBy("$tableName.$sortField", $sortOrder);
            }
        }
    }
}
