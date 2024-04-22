<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

readonly class ReservationsController extends ApiController
{
    public function index(Request $request, Response $response, string $number)
    {
        // Retrieve active reservations for flight number from DB
        $reservations = $this->reservationsRepository->findActiveReservationsByFlightNumber($number);

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