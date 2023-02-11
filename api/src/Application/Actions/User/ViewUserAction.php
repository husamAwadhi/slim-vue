<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\ActionEntity;
use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;

class ViewUserAction extends ActionEntity
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $username = (string) $this->resolveArg('username');
        /** @var UserRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->getUserOfUsername(
            username: $username
        );

        $this->logger->info("[User Info] `{$username}`");

        return $this->respondWithData($user);
    }
}
