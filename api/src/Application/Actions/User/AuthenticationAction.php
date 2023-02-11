<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\ActionEntity;
use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;

class AuthenticationAction extends ActionEntity
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $username = (string) $this->resolveData('username');
        $password = (string) $this->resolveData('password');
        /** @var UserRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->getUserOfUsernameAndPassword(
            username: $username,
            password: $password,
        );

        $data = ['token' => $this->jwt->generateToken($user)];

        $this->logger->info("[Login] `{$username}`");

        return $this->respondWithData($data);
    }
}
