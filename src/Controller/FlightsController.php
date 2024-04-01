<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Flight;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

readonly class FlightsController extends ApiController
{
    public function index(Request $request, Response $response): Response
    {
        // Retrieve the flights
        $flights = $this->entityManager->getRepository(Flight::class)
            ->findAll();

        if(!$flights) {
            return $response->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);
        }

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

        if (!$flight) {
            return $response->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);
        }

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
        $this->validator->validate($flight, $request, [Flight::CREATE_GROUP]);

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

    public function destroy(Request $request, Response $response, string $number): Response
    {
        // Query for the flight with the number: $number
        $flight = $this->entityManager->getRepository(Flight::class)
            ->findOneBy(['number' => $number]);

        // Handle not found resource
        if (!$flight) {
            return $response->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);
        }

        // Remove from DB
        $this->entityManager->remove($flight);
        $this->entityManager->flush();

        // Return response with no content status code
        return $response->withStatus(StatusCodeInterface::STATUS_NO_CONTENT);
    }

    public function update(Request $request, Response $response, string $number): Response
    {
        // Retrieve flight using flight number
        $flight = $this->entityManager->getRepository(Flight::class)
            ->findOneBy(['number' => $number]);

        // Return not found if necessary
        if (!$flight) {
            return $response->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);
        }

        // Grab the post data and map to the flight
        $flightJson = $request->getBody()->getContents();

        $flight = $this->serializer->deserialize(
            $flightJson,
            Flight::class,
            $request->getAttribute('content-type')->format(),
            [AbstractNormalizer::OBJECT_TO_POPULATE => $flight]
        );

        // Validate the post data (happy path for now..save for Error Handling section)

        // Persist
        $this->entityManager->persist($flight);
        $this->entityManager->flush();

        // Serialize the updated flight
        $jsonFlight = $this->serializer->serialize(
            ['flight' => $flight],
            $request->getAttribute('content-type')->format()
        );

        // Add the flight to the response body
        $response->getBody()->write($jsonFlight);

        return $response;
    }
}






