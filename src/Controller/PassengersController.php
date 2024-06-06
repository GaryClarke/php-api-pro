<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Passenger;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

readonly class PassengersController extends ApiController
{
    public function index(Request $request, Response $response): Response
    {
        $passengers = $this->entityManager->getRepository(Passenger::class)
            ->findAll();

        $jsonPassengers = $this->serializer->serialize(
            ['passengers' => $passengers]
        );

        $response->getBody()->write($jsonPassengers);

        return $response->withHeader('Cache-Control', 'public, max-age=600');
    }

    public function show(Request $request, Response $response, string $reference): Response
    {
        $passenger = $this->entityManager->getRepository(Passenger::class)
            ->findOneBy(['reference' => $reference]);

        $jsonPassenger = $this->serializer->serialize(
            ['passenger' => $passenger]
        );

        $response->getBody()->write($jsonPassenger);

        return $response;
    }

    public function store(Request $request, Response $response): Response
    {
        // Grab the post data and map to a passenger
        $passengerJson = $request->getBody()->getContents();

        $passenger = $this->serializer->deserialize(
            $passengerJson,
            Passenger::class
        );

        if (!$passenger->getCountryOfOrigin()) {
            $passenger->setCountryOfOrigin($passenger->getNationality());
        }

        assert($passenger instanceof Passenger);

        $passenger->setReference(time() . strtoupper(substr($passenger->getLastName(), 0, 3)));

        $this->validator->validate($passenger, $request);

        // Save the post data
        $this->entityManager->persist($passenger);
        $this->entityManager->flush();

        // Serialize the new passenger
        $jsonPassenger = $this->serializer->serialize(
            ['passenger' => $passenger],
            $request->getAttribute('content-type')->format()
        );

        // Add the passenger to the response body
        $response->getBody()->write($jsonPassenger);

        // Return the response
        return $response->withStatus(StatusCodeInterface::STATUS_CREATED);
    }

    public function destroy(Request $request, Response $response, string $reference): Response
    {
        $passenger = $this->entityManager->getRepository(Passenger::class)
            ->findOneBy(['reference' => $reference]);

        if ($passenger === null) {
            return $response->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);
        }

        $this->entityManager->remove($passenger);
        $this->entityManager->flush();

        return $response->withStatus(StatusCodeInterface::STATUS_NO_CONTENT);
    }

    public function update(Request $request, Response $response, string $reference): Response
    {
        $passenger = $this->entityManager->getRepository(Passenger::class)
            ->findOneBy(['reference' => $reference]);

        if ($passenger === null) {
            return $response->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);
        }

        // Grab the post data and map to a passenger
        $passengerJson = $request->getBody()->getContents();

        // Deserialize
        $passenger = $this->serializer->deserialize(
            data: $passengerJson,
            type: Passenger::class,
            context: [
                AbstractNormalizer::OBJECT_TO_POPULATE => $passenger,
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['reference']
            ]
        );

        $this->validator->validate($passenger, $request);

        // Persist
        $this->entityManager->persist($passenger);
        $this->entityManager->flush();

        $jsonPassenger = $this->serializer->serialize(
            ['passenger' => $passenger]
        );

        // Add the passenger to the response body
        $response->getBody()->write($jsonPassenger);

        return $response->withStatus(StatusCodeInterface::STATUS_OK);
    }
}
