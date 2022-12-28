<?php

namespace App\courseProject\Blog\Repository\Sqlite;

use App\courseProject\Blog\Exceptions\UserNotFoundException;
use App\courseProject\Blog\Post;
use App\courseProject\Blog\Repository\Interface\PostsRepositoryInterface;
use App\courseProject\Blog\UUID;

class DummyPostsRepository implements PostsRepositoryInterface
{

    public function save(Post $post): void
    {

    }

    /**
     * @throws UserNotFoundException
     */
    public function get(UUID $uuid): Post
    {
        throw new UserNotFoundException("Not found");
    }

}