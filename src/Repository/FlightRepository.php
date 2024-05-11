<?php // src/Repository/Flight.php

declare(strict_types=1);

namespace App\Repository;

use App\Repository\QueryUtils\Sort;
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

        Sort::apply(
            $sort,
            $qb,
            $tableName,
            ['departureTime', 'origin', 'destination']
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
