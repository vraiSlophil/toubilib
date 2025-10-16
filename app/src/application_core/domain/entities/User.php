<?php

namespace toubilib\core\domain\entities;

final class User
{
    public function __construct(
        private ?string $id,
        private string $email,
        private string $password,
        private int $role
    ) {

    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getHashedPassword(): string
    {
        return password_hash($this->password, PASSWORD_ARGON2ID);
    }

    public function getRole(): int
    {
        return $this->role;
    }


}