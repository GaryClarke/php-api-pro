<?php // src/Security/TokenAuthenticator.php

declare(strict_types=1);

namespace App\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenAuthenticator
{
    public function authenticate(string $jwt): object
    {
        // Retrieve jwks (public key set) from cache or auth server
        // https://some-auth-server/.well-known/jwks.json
        $jwks = '...';

        // Use the public key to decode the jwt
        $key = $this->selectKey($jwt, $jwks);

        return JWT::decode($jwt, new Key($key, 'RS256'));
    }

    private function selectKey(string $jwt, $jwks): string
    {
        $header = explode('.', $jwt)[0];
        $header = base64_decode($header);
        $kid = json_decode($header, true)['kid'] ?? null;

        // Here's what you should really do
        // Find the key in jwks using this $kid
        // Decode the base64 URL-encoded `n` and `e`.
        // Construct an RSA public key using the decoded `n` and `e`.
        // Return the RSA public key to verify the signature of the JWT.

        // But we're just going to cheat and return the key
        return file_get_contents('../public.pem');
    }
}

