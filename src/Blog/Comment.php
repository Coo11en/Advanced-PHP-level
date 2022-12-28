<?php

namespace App\courseProject\Blog;

use App\courseProject\Person\User;

class Comment
{
    private UUID $uuid;
    private Post $postUuid;
    private User $userUuid;
    private string $text;

    public function __construct(
        UUID $uuid,
        Post $postUuid,
        User $userUuid,
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
     * @return User
     */
    public function getUserUuid(): User
    {
        return $this->userUuid;
    }

    /**
     * @param User $userUuid
     */
    public function setUser(User $userUuid): void
    {
        $this->userUuid = $userUuid;
    }

    /**
     * @return Post
     */
    public function getPostUuid(): Post
    {
        return $this->postUuid;
    }

    /**
     * @param Post $postUuid
     */
    public function setPostUuid(Post $postUuid): void
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