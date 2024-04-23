<?php // src/Entity/Reservation.php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: \App\Repository\ReservationRepository::class)]
#[ORM\Table(name: 'reservations')]
class Reservation implements EntityInterface
{
    public const CANCEL_GROUP = 'cancel';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 20, unique: true)]
    private string $reference;

    #[ORM\ManyToOne(targetEntity: Flight::class)]
    #[ORM\JoinColumn(name: 'flight_id', referencedColumnName: 'id', nullable: true)]
    private Flight $flight;

    #[ORM\ManyToOne(targetEntity: Passenger::class)]
    #[ORM\JoinColumn(name: 'passenger_id', referencedColumnName: 'id', nullable: true)]
    private Passenger $passenger;

    #[ORM\Column(type: 'string', length: 10)]
    private string $seatNumber;

    #[ORM\Column(type: 'string', length: 20)]
    #[Assert\Choice(choices: ['Economy', 'Business', 'First'])]
    private string $travelClass;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Assert\NotBlank(groups: [self::CANCEL_GROUP])]
    private ?DateTimeInterface $cancelledAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): void
    {
        $this->reference = $reference;
    }

    public function getFlight(): Flight
    {
        return $this->flight;
    }

    public function setFlight(Flight $flight): void
    {
        $this->flight = $flight;
    }

    public function getPassenger(): Passenger
    {
        return $this->passenger;
    }

    public function setPassenger(Passenger $passenger): void
    {
        $this->passenger = $passenger;
    }

    public function getSeatNumber(): string
    {
        return $this->seatNumber;
    }

    public function setSeatNumber(string $seatNumber): void
    {
        $this->seatNumber = $seatNumber;
    }

    public function getTravelClass(): string
    {
        return $this->travelClass;
    }

    public function setTravelClass(string $travelClass): void
    {
        $this->travelClass = $travelClass;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string|DateTimeImmutable $createdAt): void
    {
        if (is_string($createdAt)) {
            $createdAt = new DateTimeImmutable($createdAt);
        }

        $this->createdAt = $createdAt;
    }

    public function getCancelledAt(): ?DateTimeInterface
    {
        return $this->cancelledAt;
    }

    public function setCancelledAt(string|DateTimeImmutable $cancelledAt): void
    {
        if (is_string($cancelledAt)) {
            $cancelledAt = new DateTimeImmutable($cancelledAt);
        }

        $this->cancelledAt = $cancelledAt;
    }
}
