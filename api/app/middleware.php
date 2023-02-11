<?php

declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use App\Authentication\JWT;
use Slim\App;

return function (App $app): void {
    $app->add(SessionMiddleware::class);

    // Authentication
    $app->add($app->getContainer()->get(JWT::class)->getMiddlewareInstance());
};
