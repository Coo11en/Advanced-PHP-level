<?php

namespace App\courseProject\Blog\Repository\Interface;

use App\courseProject\Blog\Like;
use App\courseProject\Blog\UUID;

interface LikesRepositoryInterface
{
    public function save(Like $like): void;
    public function get(UUID $uuid): Like;
}