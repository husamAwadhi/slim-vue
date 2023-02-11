<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Application\Settings\SettingsInterface;
use App\Authentication\JWT;
use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

abstract class ActionEntity extends Action
{
    public function __construct(
        LoggerInterface $logger,
        SettingsInterface $settings,
        protected EntityManager $entityManager,
        protected JWT $jwt,
    ) {
        parent::__construct($logger, $settings);
    }

    public function getUsernameFromToken(): string
    {
        $payload = $this->jwt->getPayloadFromToken(
            $this->request->getHeader('Authorization')[0] ?? ''
        );

        return $payload['user'];
    }

    public function getUserFromToken(): User
    {
        $username = $this->getUsernameFromToken();
        /** @var UserRepository */
        $userRepository = $this->entityManager->getRepository(User::class);

        return $userRepository->getUserOfUsername(username: $username);
    }
}
