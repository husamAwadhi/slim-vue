<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Authentication\UserInvalidCredentialsException;
use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use Doctrine\ORM\EntityRepository;

class DatabaseUserRepository extends EntityRepository implements UserRepository
{
    /**
     * {@inheritdoc}
     */
    public function getUserOfUsernameAndPassword(string $username, string $password): User
    {
        $user = $this->getUserOfUsername($username);

        if (!$user->authenticate($password)) {
            throw new UserInvalidCredentialsException();
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserOfUsername(string $username): User
    {
        $user = $this->findOneBy(['username' => $username]);
        if (!$user instanceof User) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
