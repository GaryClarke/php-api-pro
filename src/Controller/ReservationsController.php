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
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

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
        $queryParams = $request->getQueryParams();
        $page = (int) $queryParams['page'] ?? 1;
        $itemsPerPage = (int) $queryParams['itemsPerPage'] ?? 10;

        $totalItems = $this->reservationRepository->countActiveReservationsByFlightNumber($number);
        $totalPages = (int) ceil($totalItems / $itemsPerPage);
        $path = $request->getUri()->getPath();

        $links = [
            'self' => "$path?page=$page&itemsPerPage=$itemsPerPage",
            'first' => "$path?page=1&itemsPerPage=$itemsPerPage",
            'last' => "$path?page=$totalPages&itemsPerPage=$itemsPerPage",
            'prev' => $page > 1 ? "$path?page=" . ($page - 1) . "&itemsPerPage=$itemsPerPage" : null,
            'next' => $page < $totalPages ? "$path?page=" . ($page + 1) . "&itemsPerPage=$itemsPerPage" : null
        ];

        // "meta": {
        //    "totalItems": 100,
        //    "totalPages": 5,
        //    "currentPage": 1,
        //    "itemsPerPage": 20
        //  }
        $meta = [
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'itemsPerPage' => $itemsPerPage
        ];

        // Retrieve active reservations for flight number from DB
        $reservations = $this->reservationRepository->findActiveReservationsByFlightNumber(
            $number,
            $page,
            $itemsPerPage
        );

        // Serialize reservations under a reservations key
        $jsonReservations = $this->serializer->serialize(
            [
                'reservations' => $reservations,
                'links' => $links,
                'meta' => $meta
            ]
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

    public function show(Request $request, Response $response, string $reference): Response
    {
        // Find using repository
        $reservation = $this->reservationRepository->findArrayByReference($reference);

        // Exit if not found
        if (!$reservation) {
            throw new HttpNotFoundException($request, "Reservation $reference not found.");
        }

        // Serialize
        $jsonReservation = $this->serializer->serialize(
            ['reservation' => $reservation]
        );

        // Write to body
        $response->getBody()->write($jsonReservation);

        // Return a cacheable response
        return $response->withHeader('Cache-Control', 'public, max-age=600');
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

    public function update(Request $request, Response $response, string $reference): Response
    {
        // Retrieve the reservation
        $reservation = $this->reservationRepository->findByReference($reference);

        // Exit if not found
        if (!$reservation) {
            throw new HttpNotFoundException($request, "Reservation $reference not found");
        }

        // Grab the post data (body content)
        $reservationJson = $request->getBody()->getContents();

        // Deserialize into a Reservation entity object
        // (Only the fields eligible for update)
        $reservation = $this->serializer->deserialize(
            data: $reservationJson,
            type: Reservation::class,
            context: [
                AbstractNormalizer::OBJECT_TO_POPULATE => $reservation,
                AbstractNormalizer::IGNORED_ATTRIBUTES => [
                    'reference', 'flight', 'passenger', 'createdAt', 'cancelledAt'
                ]
            ]
        );

        // Validate
        $this->validator->validate($reservation, $request);

        // Persist
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        // Serialize the updated reservation
        // (Only the fields we want in the response content)
        $jsonReservation = $this->serializer->serialize(
            data: ['reservation' => $reservation],
            context: [
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['flight', 'passenger', 'cancelledAt']
            ]
        );

        // Add to the response body
        $response->getBody()->write($jsonReservation);

        // Send success (200) response
        return $response->withStatus(StatusCodeInterface::STATUS_OK);
    }
}
