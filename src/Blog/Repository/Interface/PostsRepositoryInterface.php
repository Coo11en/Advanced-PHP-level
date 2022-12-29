<?php

namespace App\courseProject\Blog\Repository\Interface;

use App\courseProject\Blog\Post;
use App\courseProject\Blog\UUID;

interface PostsRepositoryInterface
{
    public function save(Post $post): void;
    public function get(UUID $uuid): Post;
    public function delete(UUID $uuid): void;
}