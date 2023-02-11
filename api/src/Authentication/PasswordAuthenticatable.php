<?php

namespace App\Authentication;

trait PasswordAuthenticatable
{
    private string $hashingAlgorithm = PASSWORD_BCRYPT;

    private array $hashingOptions = ['cost' => 11];

    public function getPasswordHash(): string
    {
        return '';
    }

    public function hash(string $password): string
    {
        return password_hash($password, $this->hashingAlgorithm, $this->hashingOptions);
    }

    public function authenticate(string $password, ?string $hashedPassword = null): bool
    {
        return password_verify($password, $hashedPassword ?? $this->getPasswordHash());
    }
}
