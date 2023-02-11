<?php

declare(strict_types=1);

use App\Application\Actions\User\AuthenticationAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app): void {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->post('/token', AuthenticationAction::class);

    $app->get('/user/{username}', ViewUserAction::class);
};
