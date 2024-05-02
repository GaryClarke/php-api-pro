<?php // src/Repository/ReservationRepository.php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\ORM\EntityRepository;

class ReservationRepository extends EntityRepository
{
    public function findActiveReservationsByFlightNumber(
        string $flightNumber,
        int $page = 1,
        int $limit = 10
    ): array {
        return $this->createQueryBuilder('r')
            ->select('r.reference, r.seatNumber, r.travelClass, r.createdAt, f.number AS flightNumber, p.reference AS passengerReference')
            ->leftJoin('r.flight', 'f')
            ->leftJoin('r.passenger', 'p')
            ->where('r.cancelledAt IS NULL')
            ->andWhere('f.number = :flightNumber')
            ->setParameter('flightNumber', $flightNumber)
            // Calculate and set the offset (first record) ($page - 1) * $limit
            // Set limit (items per page)
            ->setFirstResult((($page - 1) * $limit))
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByReference(string $reference): ?Reservation
    {
        return $this->createQueryBuilder('r')
            ->where('r.reference = :reference') // Filter by reservation reference
            ->setParameter('reference', $reference) // Bind the reservation reference parameter
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    public function findArrayByReference(string $reference): ?array
    {
        return $this->createQueryBuilder('r')
            ->select('r.reference, r.seatNumber, r.travelClass, r.createdAt, f.number AS flightNumber, p.reference AS passengerReference')
            ->leftJoin('r.flight', 'f')
            ->leftJoin('r.passenger', 'p')
            ->where('r.reference = :reference') // Filter by reservation reference
            ->setParameter('reference', $reference) // Bind the reservation reference parameter
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }
}