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
        $flights = $this->entityManager->getRepository(Flight::class)
            ->findAll();

        $jsonFlights = $this->serializer->serialize(
            ['flights' => $flights],
            $request->getAttribute('content-type')->format()
        );

        $response->getBody()->write($jsonFlights);

        return $response->withHeader('Cache-Control', 'public, max-age=600');
    }

    public function show(Request $request, Response $response, string $number): Response
    {
        $flight = $this->entityManager->getRepository(Flight::class)
            ->findOneBy(['number' => $number]);

        $jsonFlight = $this->serializer->serialize(
            ['flight' => $flight],
            $request->getAttribute('content-type')->format()
        );

        $response->getBody()->write($jsonFlight);

        return $response;
    }

    public function store(Request $request, Response $response): Response
    {
        // Grab the post data and map to a flight
        $flightJson = $request->getBody()->getContents();

        $flight = $this->serializer->deserialize(
            $flightJson,
            Flight::class,
            $request->getAttribute('content-type')->format()
        );

        // Validate the post data (happy path for now..save for Error Handling section)

        // Save the post data
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
