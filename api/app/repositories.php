<?php

declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Infrastructure\DatabaseUserRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder): void {
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(DatabaseUserRepository::class),
    ]);
};
