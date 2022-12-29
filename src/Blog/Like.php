<?php

namespace App\courseProject\Blog;

use App\courseProject\Person\User;

class Like
{
    private UUID $uuid;
    private Post $post;
    private User $user;

    public function __construct(
        UUID $uuid,
        Post $post,
        User $user,
    )
    {
        $this->uuid = $uuid;
        $this->post = $post;
        $this->user = $user;
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
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     */
    public function setPost(Post $post): void
    {
        $this->post = $post;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

}