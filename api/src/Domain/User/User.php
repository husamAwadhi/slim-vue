<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Authentication\PasswordAuthenticatable;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;
use ReturnTypeWillChange;

#[
    Entity(repositoryClass: \App\Infrastructure\DatabaseUserRepository::class),
    Table(name: 'users')
]
class User implements JsonSerializable
{
    use PasswordAuthenticatable;

    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'string', unique: true, nullable: false)]
    private string $username;

    #[Column(type: 'string', name: 'password')]
    private string $passwordHash;

    #[Column(name: 'registered_at', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $registeredAt;

    #[Column(name: 'first_name', type: 'string', nullable: false)]
    private string $firstName;

    #[Column(name: 'last_name', type: 'string', nullable: false)]
    private string $lastName;

    public function __construct(
        string $username,
        string $firstName,
        string $lastName,
        string $password,
    ) {
        $this->username = strtolower($username);
        $this->firstName = ucfirst($firstName);
        $this->lastName = ucfirst($lastName);
        $this->passwordHash = $this->hash($password);
        $this->registeredAt = new DateTimeImmutable('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getRegisteredAt(): DateTimeImmutable
    {
        return $this->registeredAt;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getPermissions(): array
    {
        return ['view'];
    }

    #[ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'username' => $this->username,
            'firstName' => $this->firstName,
        ];
    }
}
