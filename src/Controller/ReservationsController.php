<?php // src/Controller/ReservationsController.php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\EntityValidator;
use App\Entity\Flight;
use App\Entity\Passenger;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Serializer\Serializer;
use Doctrine\ORM\EntityManagerInterface;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;

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

    public function store(Request $request, Response $response, string $number): Response
    {
        // Grab the post data and map to a flight
        $reservationJson = $request->getParsedBody();

        // Get the passenger and the flight
        $passenger = $this->entityManager->getRepository(Passenger::class)
            ->findOneBy(['reference' => $reservationJson['passengerReference']]);

        if (!$passenger) {
            throw new HttpNotFoundException($request, "Passenger {$reservationJson['passengerReference']} not found.");
        }

        // Fetch Flight entity
        $flight = $this->entityManager->getRepository(Flight::class)
            ->findOneBy(['number' => $number]);

        if (!$flight) {
            throw new HttpNotFoundException($request, "Flight {$reservationJson['flightNumber']} not found.");
        }

        // Create new Reservation
        $reservation = new Reservation();
        $reference = strstr(time() . $number, '-', true);
        $reservation->setReference($reference);
        $reservation->setFlight($flight);
        $reservation->setPassenger($passenger);
        $reservation->setSeatNumber($reservationJson['seatNumber']);
        $reservation->setTravelClass($reservationJson['travelClass']);

        // Validate the reservation
        $this->validator->validate($reservation, $request);

        // Persist the new Reservation
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        // Serialize the new reservation
        $jsonReservation = $this->serializer->serialize(
            ['reservation' => $reservation]
        );

        // Add the reservation to the response body
        $response->getBody()->write($jsonReservation);

        // Return the response
        return $response->withStatus(StatusCodeInterface::STATUS_CREATED);
    }

    public function destroy(Request $request, Response $response, string $reference): Response
    {
        return $this->cancel($request, $response, $reference);
    }

    private function cancel(Request $request, Response $response, string $reference): Response
    {
        // Find the reservation by reference
        /** @var Reservation $reservation */
        $reservation = $this->reservationRepository
            ->findOneBy(['reference' => $reference]);

        // Handle not found
        if (!$reservation) {
            throw new HttpNotFoundException($request, "Reservation $reference not found.");
        }

        // Set cancelledAt
        $reservation->setCancelledAt(new \DateTimeImmutable());

        // Validate only cancellation fields
        $this->validator->validate($reservation, $request, [Reservation::CANCEL_GROUP]);

        // Persist changes
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        // Return no content response
        return $response->withStatus(StatusCodeInterface::STATUS_NO_CONTENT);
    }
}
