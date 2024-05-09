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
        $qb = $this->createQueryBuilder('f');

        $this->applyFilters($qb, $filters);

        $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    private function applyFilters(QueryBuilder $qb, array $filters): void
    {
        // Filter by origin if provided
        if (isset($filters['origin'])) {
            $qb->andWhere('f.origin = :origin')
                ->setParameter('origin', $filters['origin']);
        }

        // Filter by destination if provided
        if (isset($filters['destination'])) {
            $qb->andWhere('f.destination = :destination')
                ->setParameter('destination', $filters['destination']);
        }

        // Filter by departure date if provided
        if (isset($filters['departureDate'])) {
            $qb->andWhere('SUBSTRING(f.departureTime, 1, 10) = :departureDate')
                ->setParameter('departureDate', $filters['departureDate']);
        }
    }
}
