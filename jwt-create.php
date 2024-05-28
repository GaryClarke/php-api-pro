<?php

require_once 'vendor/autoload.php';

$issuedAt = time();
$expirationTime = $issuedAt + 3600;  // Token is valid for 1 hour

$payload = [
    "iss" => "https://yourdomain.com",  // The issuer of the token
    "sub" => "user12345",               // The subject of the token (e.g., user ID)
    "aud" => "https://jet-fu.com",     // The audience of the token
    "exp" => $expirationTime,           // Token expiration time
    "iat" => $issuedAt,                 // Issuance time
    "jti" => bin2hex(random_bytes(16))  // JWT ID to prevent replay attacks
];

// Optionally, you might want to include additional claims:
$payload['role'] = 'partner';            // Custom claim for user role
$payload['email'] = 'user@example.com';// Custom claim for user email

$privateKey = file_get_contents('private.pem');

$uuid = \Ramsey\Uuid\Uuid::uuid4();
$kid = $uuid->toString();

// Generate the JWT


$jwt = \Firebase\JWT\JWT::encode($payload, $privateKey, 'RS256', $kid);

dd($jwt);