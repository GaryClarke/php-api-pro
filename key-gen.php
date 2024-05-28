<?php

require_once 'vendor/autoload.php';


// Configuration settings for the key generation
$config = [
    "digest_alg" => "sha256",
    "private_key_bits" => 2048,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
];

// Create the private key resource
$privateKeyResource = openssl_pkey_new($config);

// Extract the private key from the resource
openssl_pkey_export($privateKeyResource, $privateKey);

// Get the public key that corresponds to the private key
$publicKeyDetails = openssl_pkey_get_details($privateKeyResource);
$publicKey = $publicKeyDetails["key"];

// Save the private key and public key to disk
file_put_contents('private.pem', $privateKey);
file_put_contents('public.pem', $publicKey);

echo "Keys generated successfully:\n";
echo "Private Key: private.pem\n";
echo "Public Key: public.pem\n";

