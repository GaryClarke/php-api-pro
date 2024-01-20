<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'flights')]
class Flight
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 8)]
    private string $number;

    #[ORM\Column(type: 'string', length: 3)]
    private string $origin;

    #[ORM\Column(type: 'string', length: 3)]
    private string $destination;

    #[ORM\Column(name: 'departure_time', type: 'datetime_immutable')]
    private DateTimeImmutable $departureTime;

    #[ORM\Column(name: 'arrival_time', type: 'datetime_immutable')]
    private DateTimeImmutable $arrivalTime;

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    public function getOrigin(): string
    {
        return $this->origin;
    }

    public function setOrigin(string $origin): void
    {
        $this->origin = $origin;
    }

    public function getDestination(): string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): void
    {
        $this->destination = $destination;
    }

    public function getDepartureTime(): DateTimeImmutable
    {
        return $this->departureTime;
    }

    public function setDepartureTime(DateTimeImmutable $departureTime): void
    {
        $this->departureTime = $departureTime;
    }

    public function getArrivalTime(): DateTimeImmutable
    {
        return $this->arrivalTime;
    }

    public function setArrivalTime(DateTimeImmutable $arrivalTime): void
    {
        $this->arrivalTime = $arrivalTime;
    }
}