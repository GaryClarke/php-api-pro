<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Flight;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

readonly class FlightsController extends ApiController
{
    public function index(Request $request, Response $response): Response
    {
        // Retrieve the flights
        $flights = $this->entityManager->getRepository(Flight::class)
            ->findAll();

        // Serialize the flights
        $jsonFlights = $this->serializer->serialize(['flights' => $flights], 'json');

        // Return the response containing the flights
        $response->getBody()->write($jsonFlights);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function show(Request $request, Response $response, string $number): Response
    {
        $flight = $this->entityManager->getRepository(Flight::class)
            ->findOneBy(['number' => $number]);

        $jsonFlight = $this->serializer->serialize(['flight' => $flight], 'json');

        $response->getBody()->write($jsonFlight);

        return $response->withHeader('Content-Type', 'application/json');
        // response.data.flight.origin
    }
}






