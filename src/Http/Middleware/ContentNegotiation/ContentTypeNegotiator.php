<?php // src/Http/Middleware/ContentNegotiation/ContentTypeNegotiator.php

declare(strict_types=1);

namespace App\Http\Middleware\ContentNegotiation;

use App\Serializer\Serializer;
use Psr\Http\Message\ServerRequestInterface;

class ContentTypeNegotiator implements ContentNegotiationInterface
{
    public function __construct(private Serializer $serializer)
    {
    }

    public function negotiate(ServerRequestInterface $request): ServerRequestInterface
    {
        // Do what we want to do with the received request
        $accept = $request->getHeaderLine('Accept');

        $requestedFormats = explode(',', $accept);

        foreach ($requestedFormats as $requestedFormat) {
            if ($format = ContentType::tryFrom($requestedFormat)) {
                break;
            }
        }

        $contentType = ($format ?? ContentType::JSON);

        // Set format on serializer
        $this->serializer->setFormat($contentType->format());

        return $request->withAttribute('content-type', $contentType);
    }
}
