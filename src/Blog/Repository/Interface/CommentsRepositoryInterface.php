<?php


namespace App\courseProject\Blog\Repository\Interface;

use App\courseProject\Blog\Comment;
use App\courseProject\Blog\UUID;

interface CommentsRepositoryInterface
{
    public function save(Comment $comment): void;
    public function get(UUID $uuid): Comment;
}