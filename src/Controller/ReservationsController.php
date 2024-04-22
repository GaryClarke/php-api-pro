<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\EntityValidator;
use App\Repository\ReservationRepository;
use App\Serializer\Serializer;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

readonly class ReservationsController extends ApiController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        Serializer $serializer,
        EntityValidator $validator,
        private ReservationRepository $reservationRepository
    )
    {
        parent::__construct($entityManager, $serializer, $validator);
    }

    public function index(Request $request, Response $response, string $number): Response
    {
        // Retrieve active reservations for flight number from DB
        $reservations = $this->reservationRepository->findActiveReservationsByFlightNumber($number);

        // Serialize reservations under a reservations key
        $jsonReservations = $this->serializer->serialize(
            ['reservations' => $reservations]
        );

        // Write the reservations to the response body
        $response->getBody()->write($jsonReservations);

        // Return a cacheable response
        return $response->withHeader('Cache-Control', 'public, max-age=600');
    }
}