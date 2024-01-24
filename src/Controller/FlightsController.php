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
        // Retrieve flight from the DB
        $flights = $this->entityManager->getRepository(Flight::class)
            ->findAll();

        // Serialize the flight
        $flightsJson = $this->serializer->serialize(['flights' => $flights], 'json');

        // Write serialized flight to response body
        $response->getBody()->write($flightsJson);

        // Return the response
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function show(Request $request, Response $response, string $number): Response
    {
        $flight = $this->entityManager->getRepository(Flight::class)
            ->findOneBy(['number' => $number]);

        $flightJson = $this->serializer->serialize(['flight' => $flight], 'json');

        $response->getBody()->write($flightJson);

        return $response->withHeader('Content-Type', 'application/json');
    }
}












