<?php

namespace App\courseProject\Blog\Repository\Interface;

use App\courseProject\Blog\UUID;
use App\courseProject\Person\User;

interface UsersRepositoryInterface
{
    public function save(User $user): void;
    public function get(UUID $uuid): User;
}