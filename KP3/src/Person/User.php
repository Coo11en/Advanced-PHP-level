<?php

namespace App\courseProject\Person;

use App\courseProject\Blog\UUID;

class User
{
    private UUID $uuid;
    private string $username;
    private string $firstname;
    private string $secondname;

    public function __construct(
        UUID $uuid,
        string $username,
        string $firstname,
        string $secondname
    )
    {
        $this->uuid = $uuid;
        $this->username = $username;
        $this->firstname = $firstname;
        $this->secondname = $secondname;
    }

    /**
     * @return UUID
     */
    public function getUuid(): UUID
    {
        return $this->uuid;
    }

    /**
     * @param UUID $uuid
     */
    public function setUuid(UUID $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getSecondname(): string
    {
        return $this->secondname;
    }

    /**
     * @param string $secondname
     */
    public function setSecondname(string $secondname): void
    {
        $this->secondname = $secondname;
    }

    public function __toString()
    {
        return $this->firstname . ' ' . $this->secondname;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }
}