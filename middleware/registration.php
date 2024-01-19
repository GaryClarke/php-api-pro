<?php

declare(strict_types=1);

use App\Http\Middleware\MiddlewareRegistrar;

$middlewareRegistrar = new MiddlewareRegistrar($app);

$middlewareRegistrar->register();
