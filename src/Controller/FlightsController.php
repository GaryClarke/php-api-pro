<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Flight;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

readonly class FlightsController extends ApiController
{
    public function index(Request $request, Response $response): Response
    {
        // Retrieve the flights
        $flights = $this->entityManager->getRepository(Flight::class)
            ->findAll();

        // Serialize the flights
        $jsonFlights = $this->serializer->serialize(
            ['flights' => $flights],
            $request->getAttribute('content-type')->format()
        );

        // Return the response containing the flights
        $response->getBody()->write($jsonFlights);

        return $response->withHeader('Cache-Control', 'public, max-age=600');
    }

    public function show(Request $request, Response $response, string $number): Response
    {
        $flight = $this->entityManager->getRepository(Flight::class)
            ->findOneBy(['number' => $number]);

        // Serialize the flights
        $jsonFlight = $this->serializer->serialize(
            ['flight' => $flight],
            $request->getAttribute('content-type')->format()
        );

        $response->getBody()->write($jsonFlight);

        return $response->withHeader('Cache-Control', 'public, max-age=600');
    }

    public function store(Request $request, Response $response): Response
    {
        // Grab the post data
        $flightJson = $request->getBody()->getContents();

        // deserialize into a flight
        $flight = $this->serializer->deserialize(
            $flightJson,
            Flight::class,
            $request->getAttribute('content-type')->format()
        );

        // Validate the post data (happy path for now..save for Error Handling section)

        // Save the flight to the DB
        $this->entityManager->persist($flight);
        $this->entityManager->flush();

        // Serialize the new flight
        $jsonFlight = $this->serializer->serialize(
            ['flight' => $flight],
            $request->getAttribute('content-type')->format()
        );

        // Add the flight to the response body
        $response->getBody()->write($jsonFlight);

        // Return the response
        return $response->withStatus(StatusCodeInterface::STATUS_CREATED);
    }
}






