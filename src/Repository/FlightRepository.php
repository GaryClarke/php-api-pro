<?php // src/Repository/Flight.php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class FlightRepository extends EntityRepository
{
    public function findFlights(int $page = 1, int $limit = 10)
    {
        return $this->createQueryBuilder('f')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
