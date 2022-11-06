<?php

namespace App\courseProject\Blog;

class Comment
{
    private UUID $uuid;
    private string $postUuid;
    private string $userUuid;
    private string $text;

    public function __construct(
        UUID $uuid,
        string $postUuid,
        string $userUuid,
        string $text
    )
    {
        $this->uuid = $uuid;
        $this->userUuid = $userUuid;
        $this->postUuid = $postUuid;
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
     * @return string
     */
    public function getUserUuid(): string
    {
        return $this->userUuid;
    }

    /**
     * @param string $userUuid
     */
    public function setUser(string $userUuid): void
    {
        $this->userUuid = $userUuid;
    }

    /**
     * @return string
     */
    public function getPostUuid(): string
    {
        return $this->postUuid;
    }

    /**
     * @param string $postUuid
     */
    public function setPostUuid(string $postUuid): void
    {
        $this->postUuid = $postUuid;
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
        return $this->text;
    }
}