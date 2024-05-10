<?php // src/Repository/Flight.php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class FlightRepository extends EntityRepository
{
    public function findFlights(
        int $page = 1,
        int $limit = 10,
        array $filters = []
    )
    {
        $tableName = 'flights';

        $qb = $this->createQueryBuilder($tableName);

        $this->applyFilters($qb, $filters, $tableName);

        $sort = $filters['sort'] ?? null;

        $this->applySort(
            $sort,
            $qb,
            $tableName
        );

        $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    public function countFilteredFlights(array $filters = []): int
    {
        $tableName = 'flights';

        $qb = $this->createQueryBuilder($tableName)
            ->select("count($tableName.id)");

        $this->applyFilters($qb, $filters, $tableName);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    private function applySort(
        ?string $sort,
        QueryBuilder $qb,
        string $tableName
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
            if (in_array($sortField, ['departureTime', 'origin', 'destination'])) {
                // Add orderBy if so
                $qb->addOrderBy("$tableName.$sortField", $sortOrder);
            }
        }
    }

    private function applyFilters(
        QueryBuilder $qb,
        array $filters,
        string $tableName
    ): void {
        // Filter by origin if provided
        if (isset($filters['origin'])) {
            $qb->andWhere("$tableName.origin = :origin")
                ->setParameter('origin', $filters['origin']);
        }

        // Filter by destination if provided
        if (isset($filters['destination'])) {
            $qb->andWhere("$tableName.destination = :destination")
                ->setParameter('destination', $filters['destination']);
        }

        // Filter by departure date if provided
        if (isset($filters['departureDate'])) {
            $qb->andWhere("SUBSTRING($tableName.departureTime, 1, 10) = :departureDate")
                ->setParameter('departureDate', $filters['departureDate']);
        }
    }
}
