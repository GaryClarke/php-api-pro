<?php // src/Repository/ReservationRepository.php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Reservation;
use App\Repository\QueryUtils\Sort;
use Doctrine\ORM\EntityRepository;

class ReservationRepository extends EntityRepository
{
    public function findActiveReservationsByFlightNumber(
        string $flightNumber,
        int $page = 1,
        int $limit = 10,
        $filters = []
    ): array {
        $tableName = 'reservations';

        $qb = $this->createQueryBuilder($tableName);

        $qb->select("$tableName.reference, $tableName.seatNumber, $tableName.travelClass, $tableName.createdAt, f.number AS flightNumber, p.reference AS passengerReference")
            ->leftJoin("$tableName.flight", "f")
            ->leftJoin("$tableName.passenger", "p")
            ->where("$tableName.cancelledAt IS NULL")
            ->andWhere('f.number = :flightNumber') // Filter by flight number
            ->setParameter('flightNumber', $flightNumber); // Bind the flight number parameter

        Sort::apply(
            $filters['sort'] ?? null,
            $qb,
            $tableName,
            ['createdAt', 'travelClass', 'seatNumber']
        );

        $qb->setFirstResult(($page - 1) * $limit) // Calculate offset
        ->setMaxResults($limit); // Set limit

        $_ = $qb->getQuery();

        dd($_->getSQL(), $_->getDQL(), $_->getParameters());

//            ->getResult();
    }

    public function countActiveReservationsByFlightNumber(string $flightNumber): int
    {
        $count = $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->leftJoin('r.flight', 'f')
            ->where('r.cancelledAt IS NULL')
            ->andWhere('f.number = :flightNumber')
            ->setParameter('flightNumber', $flightNumber)
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $count;
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