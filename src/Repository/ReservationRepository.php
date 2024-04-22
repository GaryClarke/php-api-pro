<?php // src/Repository/ReservationRepository.php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class ReservationRepository extends EntityRepository
{
    public function findActiveReservationsByFlightNumber(string $flightNumber): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.reference, r.seatNumber, r.travelClass, r.createdAt, f.number AS flightNumber, p.reference AS passengerReference')
            ->leftJoin('r.flight', 'f')
            ->leftJoin('r.passenger', 'p')
            ->where('r.cancelledAt IS NULL')
            ->andWhere('f.number = :flightNumber')
            ->setParameter('flightNumber', $flightNumber)
            ->getQuery()
            ->getResult();
    }
}