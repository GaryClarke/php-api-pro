<?php // src/Entity/Passenger.php

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'passengers')]
class Passenger implements ResourceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 20, unique: true)]
    private string $reference;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank()]
    private string $firstNames;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank()]
    private string $lastName;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    #[Assert\NotBlank()]
    private ?string $passportNumber = null;

    #[ORM\Column(type: 'date')]
    #[Assert\NotBlank()]
    private DateTimeInterface $dateOfBirth;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\NotBlank()]
    private ?string $nationality = null;

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): void
    {
        $this->reference = $reference;
    }

    public function getFirstNames(): string
    {
        return $this->firstNames;
    }

    public function setFirstNames(string $firstNames): void
    {
        $this->firstNames = $firstNames;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getPassportNumber(): ?string
    {
        return $this->passportNumber;
    }

    public function setPassportNumber(?string $passportNumber): void
    {
        $this->passportNumber = $passportNumber;
    }

    public function getDateOfBirth(): string
    {
        return $this->dateOfBirth->format('Y-m-d');
    }

    public function setDateOfBirth(string|DateTimeImmutable $dateOfBirth): void
    {
        if (is_string($dateOfBirth)) {
            $dateOfBirth = new DateTimeImmutable($dateOfBirth);
        }

        $this->dateOfBirth = $dateOfBirth;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): void
    {
        $this->nationality = $nationality;
    }
}
