<?php

declare(strict_types=1);

namespace App\Security;

class TokenAuthenticator
{
    public function authenticate(string $jwt)
    {
        dd($jwt);
    }
}