<?php

declare(strict_types=1);

namespace App\Domain\User;

interface UserRepository
{
    /**
     * @throws UserNotFoundException
     * @throws \App\Authentication\UserInvalidCredentialsException
     */
    public function getUserOfUsernameAndPassword(string $username, string $password): User;

    /**
     * @throws \App\Domain\User\UserNotFoundException
     */
    public function getUserOfUsername(string $username): User;
}
