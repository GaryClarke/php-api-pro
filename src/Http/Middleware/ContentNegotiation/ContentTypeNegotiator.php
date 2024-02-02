<?php

declare(strict_types=1);

namespace App\Http\Middleware\ContentNegotiation;

use Psr\Http\Message\ServerRequestInterface;

class ContentTypeNegotiator implements ContentNegotiatorInterface
{
    public function negotiate(ServerRequestInterface $request): ServerRequestInterface
    {

        /**
         * 1. What kind of thing is the code doing? -  Content negotiation
         * 2. What are the inputs? - Request
         * 3. What are the outputs? - Request
         */
        $accept = $request->getHeaderLine('Accept');

        $requestedFormats = explode(',', $accept);

        foreach ($requestedFormats as $requestedFormat) {
            if ($format = ContentType::tryFrom($requestedFormat)) {
                break;
            }
        }

        $contentType = ($format ?? ContentType::JSON)->value;

        return $request->withAttribute('content-type', $contentType);

        // ==================
    }
}
