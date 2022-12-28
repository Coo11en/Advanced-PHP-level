<?php

namespace App\courseProject\Blog;

use App\courseProject\Person\User;

class Post
{
    private UUID $uuid;
    private User $userUuid;
    private string $header;
    private string $text;

    public function __construct(
        UUID $uuid,
        User $userUuid,
        string $header,
        string $text
    )
    {
        $this->uuid = $uuid;
        $this->userUuid = $userUuid;
        $this->header = $header;
        $this->text = $text;
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
     * @return User
     */
    public function getUserUuid(): User
    {
        return $this->userUuid;
    }

    /**
     * @param User $userUuid
     */
    public function setUserUuid(User $userUuid): void
    {
        $this->userUuid = $userUuid;
    }

    /**
     * @return string
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @param string $header
     */
    public function setHeader(string $header): void
    {
        $this->header = $header;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }
    public function __toString()
    {
        return $this->header . ' >>> ' . $this->text;
    }
}