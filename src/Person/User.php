<?php

namespace App\courseProject\Person;

use App\courseProject\Blog\UUID;

class User
{
    private UUID $uuid;
    private string $username;
    private string $hashedPassword;
    private string $firstname;
    private string $secondname;

    public function __construct(
        UUID $uuid,
        string $username,
        string $hashedPassword,
        string $firstname,
        string $secondname
    )
    {
        $this->uuid = $uuid;
        $this->username = $username;
        $this->hashedPassword = $hashedPassword;
        $this->firstname = $firstname;
        $this->secondname = $secondname;
    }

    // Переименовали функцию
    public function hashedPassword(): string
    {
        return $this->hashedPassword;
    }

    // Функция для создания нового пользователя
    public static function createFrom(
        string $username,
        string $password,
        string $firstname,
        string $secondname,
    ): self
    {
        $uuid = UUID::random();
        return new self(
            $uuid,
            $username,
            self::hash($password, $uuid),
            $firstname,
            $secondname,
        );
    }
    private static function hash(string $password, UUID $uuid): string
    {
        // Используем UUID в качестве соли
        return hash('sha256', $uuid . $password);
    }

    // Функция для проверки предъявленного пароля
    public function checkPassword(string $password): bool
    {
        // Передаём UUID пользователя
        // в функцию хеширования пароля
        return $this->hashedPassword
            === self::hash($password, $this->uuid);
    }


    public function getUsername(): string
    {
        return $this->username;
    }

    public function getUuid(): UUID
    {
        return $this->uuid;
    }

    /**
     * @return Name
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @return Name
     */
    public function getSecondname(): string
    {
        return $this->secondname;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->firstname . ' ' . $this->secondname;
    }
}